<?php

namespace App\Services;

/**
 * CCAvenue payment gateway helper.
 *
 * CCAvenue uses AES-128-CBC encryption with a fixed IV and the MD5 of the
 * merchant's working key. The merchant posts an encrypted request string to
 * CCAvenue; CCAvenue redirects the user to their hosted page; after payment
 * CCAvenue POSTs an encrypted response back to the redirect_url.
 *
 * Env vars:
 *   CCAVENUE_MERCHANT_ID
 *   CCAVENUE_ACCESS_CODE
 *   CCAVENUE_WORKING_KEY
 *   CCAVENUE_BASE_URL  (test: https://test.ccavenue.com,
 *                       live: https://secure.ccavenue.com)
 */
class CCAvenueService
{
    // CCAvenue's fixed IV, published in their integration kit.
    private const IV = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f";

    public function merchantId(): ?string  { return config('services.ccavenue.merchant_id'); }
    public function accessCode(): ?string  { return config('services.ccavenue.access_code'); }
    public function workingKey(): ?string  { return config('services.ccavenue.working_key'); }
    public function baseUrl(): string      { return rtrim(config('services.ccavenue.base_url', 'https://test.ccavenue.com'), '/'); }

    public function isConfigured(): bool
    {
        return $this->merchantId() && $this->accessCode() && $this->workingKey();
    }

    public function transactionUrl(): string
    {
        return $this->baseUrl() . '/transaction/transaction.do?command=initiateTransaction';
    }

    /**
     * Build the raw request string CCAvenue expects, then encrypt it.
     */
    public function buildEncryptedRequest(array $fields): string
    {
        $query = http_build_query($fields);
        return $this->encrypt($query);
    }

    /**
     * Decrypt the encResp CCAvenue posts back to our redirect_url.
     * Returns the parsed query-string as an associative array.
     */
    public function decodeResponse(string $encResp): array
    {
        $plain = $this->decrypt($encResp);
        parse_str($plain, $parsed);
        return $parsed;
    }

    protected function encrypt(string $plaintext): string
    {
        $key = hex2bin(md5($this->workingKey()));
        $cipher = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, self::IV);
        return bin2hex($cipher);
    }

    protected function decrypt(string $hexCipher): string
    {
        $key = hex2bin(md5($this->workingKey()));
        return (string) openssl_decrypt(hex2bin($hexCipher), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, self::IV);
    }
}
