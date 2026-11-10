<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OfficerSubscriber;
use App\Models\SoldierSubscriber;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function __construct(protected SmsService $sms) {}

    public function officerForm()
    {
        return view('public.subscribe.officer');
    }

    public function soldierForm()
    {
        return view('public.subscribe.soldier');
    }

    public function officerStore(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:150',
            'mobile'    => 'required|string|max:20',
            'email'     => 'required|email|max:150',
        ]);

        $otp = (string) random_int(100000, 999999);

        $sub = OfficerSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                ...$data,
                'status'            => 'unverified',
                'otp'               => $otp,
                'otp_expires_at'    => now()->addMinutes(15),
                'ip_address'        => $request->ip(),
                'unsubscribe_token' => $this->issueUnsubToken(),
            ]
        );

        $this->sendOtp($sub->email, $sub->mobile, $sub->full_name, $otp);

        return redirect()->route('subscribe.verify', ['type' => 'officer', 'id' => $sub->id])
            ->with('info', 'Verification code sent to '.$sub->email.' and '.$this->maskMobile($sub->mobile));
    }

    public function soldierStore(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'mobile'     => 'required|string|max:20',
            'email'      => 'required|email|max:150',
        ]);

        $otp = (string) random_int(100000, 999999);

        $sub = SoldierSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                ...$data,
                'status'            => 'unverified',
                'otp'               => $otp,
                'otp_expires_at'    => now()->addMinutes(15),
                'ip_address'        => $request->ip(),
                'unsubscribe_token' => $this->issueUnsubToken(),
            ]
        );

        $this->sendOtp($sub->email, $sub->mobile, $sub->first_name, $otp);

        return redirect()->route('subscribe.verify', ['type' => 'soldier', 'id' => $sub->id])
            ->with('info', 'Verification code sent to '.$sub->email.' and '.$this->maskMobile($sub->mobile));
    }

    public function verifyForm(string $type, int $id)
    {
        abort_unless(in_array($type, ['officer', 'soldier']), 404);
        return view('public.subscribe.verify', compact('type', 'id'));
    }

    public function verify(Request $request, string $type, int $id)
    {
        $data = $request->validate(['otp' => 'required|digits:6']);

        $model = $type === 'officer' ? OfficerSubscriber::class : SoldierSubscriber::class;
        $sub = $model::findOrFail($id);

        if ($sub->otp !== $data['otp'] || $sub->otp_expires_at?->isPast()) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $sub->update([
            'status'      => 'subscribed',
            'verified_at' => now(),
            'otp'         => null,
        ]);

        return redirect('/')->with('success', 'Verified! You are subscribed.');
    }

    public function resend(Request $request, string $type, int $id)
    {
        abort_unless(in_array($type, ['officer', 'soldier']), 404);

        $model = $type === 'officer' ? OfficerSubscriber::class : SoldierSubscriber::class;
        $sub = $model::findOrFail($id);

        // Rate-limit per subscriber: at most one resend every 60 seconds
        if ($sub->otp_expires_at && $sub->otp_expires_at->diffInSeconds(now()->addMinutes(15)) < 60) {
            return back()->withErrors(['otp' => 'Please wait a minute before requesting a new code.']);
        }

        $otp = (string) random_int(100000, 999999);
        $sub->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(15),
        ]);

        $name = $sub->full_name ?? $sub->first_name;
        $this->sendOtp($sub->email, $sub->mobile, $name, $otp);

        return back()->with('info', 'New code sent to '.$sub->email.' and '.$this->maskMobile($sub->mobile));
    }

    public function unsubscribe(string $token)
    {
        $sub = OfficerSubscriber::where('unsubscribe_token', $token)->first()
             ?? SoldierSubscriber::where('unsubscribe_token', $token)->first();

        if (! $sub) {
            return redirect('/')->withErrors(['token' => 'Invalid unsubscribe link.']);
        }

        $sub->update(['status' => 'unsubscribed', 'unsubscribed_at' => now()]);

        return view('public.subscribe.unsubscribed', ['email' => $sub->email]);
    }

    /** Fire-and-forget: email via queue, SMS inline (MSG91 responds in ~200ms). */
    protected function sendOtp(string $email, string $mobile, string $name, string $otp): void
    {
        Mail::to($email)->queue(new OtpMail($otp, $name));
        $this->sms->sendOtp($mobile, $otp);
    }

    protected function issueUnsubToken(): string
    {
        do {
            $token = Str::random(48);
        } while (
            OfficerSubscriber::where('unsubscribe_token', $token)->exists()
            || SoldierSubscriber::where('unsubscribe_token', $token)->exists()
        );
        return $token;
    }

    protected function maskMobile(string $mobile): string
    {
        $digits = preg_replace('/\D+/', '', $mobile);
        if (strlen($digits) < 4) return '***';
        return str_repeat('*', strlen($digits) - 4) . substr($digits, -4);
    }
}
