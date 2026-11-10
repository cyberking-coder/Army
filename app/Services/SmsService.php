<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * MSG91 SMS gateway wrapper.
 *
 * Uses MSG91's Flow API (DLT-compliant, mandatory for Indian SMS).
 * Env vars:
 *   MSG91_AUTH_KEY        — your MSG91 account auth key
 *   MSG91_SENDER_ID       — 6-char DLT-approved sender ID (e.g. "TRARMY")
 *   MSG91_OTP_TEMPLATE_ID — DLT template id for your OTP message
 *
 * Template example (what you register on MSG91 DLT):
 *   "##OTP## is your Territorial Army verification code. Valid 15 min."
 *
 * When any of these is missing (local dev), SMS is written to the
 * Laravel log instead of being sent.
 */
class SmsService
{
    protected string $base = 'https://control.msg91.com/api/v5';

    public function authKey(): ?string    { return config('services.msg91.auth_key'); }
    public function senderId(): ?string   { return config('services.msg91.sender_id'); }
    public function templateId(): ?string { return config('services.msg91.otp_template_id'); }

    public function isConfigured(): bool
    {
        return $this->authKey() && $this->senderId() && $this->templateId();
    }

    /**
     * Send an OTP via MSG91. Returns true on success, false on failure.
     * Mobile must be 10-digit Indian number or +91-prefixed.
     */
    public function sendOtp(string $mobile, string $otp): bool
    {
        $mobile = $this->normalise($mobile);

        if (! $this->isConfigured()) {
            Log::info('SMS (dev): OTP to '.$mobile.' => '.$otp);
            return true;
        }

        try {
            $response = Http::withHeaders([
                'authkey'      => $this->authKey(),
                'Content-Type' => 'application/JSON',
            ])->post($this->base.'/flow/', [
                'template_id' => $this->templateId(),
                'sender'      => $this->senderId(),
                'short_url'   => '0',
                'recipients'  => [
                    [
                        'mobiles' => $mobile,
                        'OTP'     => $otp,
                    ],
                ],
            ]);

            if (! $response->successful()) {
                Log::warning('MSG91 send failed', ['body' => $response->body(), 'status' => $response->status()]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('MSG91 request errored', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send a generic transactional SMS (not OTP). Uses the same Flow API
     * endpoint but with a different template id passed in.
     */
    public function send(string $mobile, string $templateId, array $vars = []): bool
    {
        $mobile = $this->normalise($mobile);

        if (! $this->authKey() || ! $this->senderId()) {
            Log::info('SMS (dev): to '.$mobile.' template '.$templateId.' vars '.json_encode($vars));
            return true;
        }

        try {
            $response = Http::withHeaders([
                'authkey'      => $this->authKey(),
                'Content-Type' => 'application/JSON',
            ])->post($this->base.'/flow/', [
                'template_id' => $templateId,
                'sender'      => $this->senderId(),
                'recipients'  => [array_merge(['mobiles' => $mobile], $vars)],
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('MSG91 generic send errored', ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected function normalise(string $mobile): string
    {
        $digits = preg_replace('/\D+/', '', $mobile);

        // Indian 10-digit number - prefix 91
        if (strlen($digits) === 10) {
            return '91'.$digits;
        }

        // Already includes 91
        if (strlen($digits) >= 12 && str_starts_with($digits, '91')) {
            return $digits;
        }

        return $digits;
    }
}
