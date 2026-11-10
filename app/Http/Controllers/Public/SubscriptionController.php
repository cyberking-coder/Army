<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\OfficerSubscriber;
use App\Models\SoldierSubscriber;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
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

        $sub = OfficerSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                ...$data,
                'status'         => 'unverified',
                'otp'            => (string) random_int(100000, 999999),
                'otp_expires_at' => now()->addMinutes(15),
                'ip_address'     => $request->ip(),
            ]
        );

        // Phase 6 wires the actual OTP email. For now flash to the page.
        session()->flash('otp_sent', "OTP (demo): {$sub->otp}");

        return redirect()->route('subscribe.verify', ['type' => 'officer', 'id' => $sub->id]);
    }

    public function soldierStore(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'mobile'     => 'required|string|max:20',
            'email'      => 'required|email|max:150',
        ]);

        $sub = SoldierSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                ...$data,
                'status'         => 'unverified',
                'otp'            => (string) random_int(100000, 999999),
                'otp_expires_at' => now()->addMinutes(15),
                'ip_address'     => $request->ip(),
            ]
        );

        session()->flash('otp_sent', "OTP (demo): {$sub->otp}");

        return redirect()->route('subscribe.verify', ['type' => 'soldier', 'id' => $sub->id]);
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
}
