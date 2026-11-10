<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Fast2SMS SMS gateway wrapper.
 *
 * Uses Fast2SMS's "otp" route — no DLT registration or template approval
 * needed. Fast2SMS handles sender ID / DLT compliance on their side; we
 * just send the OTP number and the mobile.
 *
 * Env var:
 *   FAST2SMS_API_KEY  — your Fast2SMS API key (see https://www.fast2sms.com)
 *
 * When empty, SMS is written to the Laravel log instead of being sent.
 */
class SmsService
{
    protected string $endpoint = 'https://www.fast2sms.com/dev/bulkV2';

    public function apiKey(): ?string
    {
        return config('services.fast2sms.api_key');
    }

    public function isConfigured(): bool
    {
        return (bool) $this->apiKey();
    }

    /**
     * Send a 6-digit OTP to the given mobile. Returns true on success.
     * Mobile accepts 10-digit Indian numbers with or without +91 prefix.
     */
    public function sendOtp(string $mobile, string $otp): bool
    {
        $number = $this->normalise($mobile);

        if (! $this->isConfigured()) {
            Log::info('SMS (dev): OTP to '.$number.' => '.$otp);
            return true;
        }

        try {
            $response = Http::asForm()
                ->withHeaders(['authorization' => $this->apiKey()])
                ->post($this->endpoint, [
                    'variables_values' => $otp,
                    'route'            => 'otp',
                    'numbers'          => $number,
                ]);

            $ok = $response->successful()
                && ($response->json('return') === true || $response->json('status_code') === 200);

            if (! $ok) {
                Log::warning('Fast2SMS send failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Fast2SMS request errored', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send a plain transactional SMS using Fast2SMS's "q" (Quick SMS) route.
     * Use this for order shipped notifications, admin alerts, etc.
     */
    public function send(string $mobile, string $message): bool
    {
        $number = $this->normalise($mobile);

        if (! $this->isConfigured()) {
            Log::info('SMS (dev): to '.$number.' => '.$message);
            return true;
        }

        try {
            $response = Http::asForm()
                ->withHeaders(['authorization' => $this->apiKey()])
                ->post($this->endpoint, [
                    'message' => $message,
                    'route'   => 'q',
                    'numbers' => $number,
                ]);

            return $response->successful()
                && ($response->json('return') === true || $response->json('status_code') === 200);
        } catch (\Throwable $e) {
            Log::error('Fast2SMS generic send errored', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Fast2SMS wants a 10-digit Indian mobile with NO country code prefix.
     */
    protected function normalise(string $mobile): string
    {
        $digits = preg_replace('/\D+/', '', $mobile);

        // Strip leading 91 if present
        if (strlen($digits) >= 12 && str_starts_with($digits, '91')) {
            $digits = substr($digits, 2);
        }

        // Strip leading 0 if present
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return $digits;
    }
}
