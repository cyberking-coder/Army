# SMS Setup — Fast2SMS

Fast2SMS is a low-cost Indian SMS provider that handles DLT compliance on their side, so we don't need the usual 1–2 day DLT registration wait. OTP route starts at **~₹0.10 per SMS**.

## 1. Sign up

<https://www.fast2sms.com/registration>

Add your mobile number — they'll verify with an OTP.

**Starting balance:** ₹100 minimum top-up (that's ~1,000 OTPs).

## 2. Get your API key

1. Log in to Fast2SMS → **Dev API** (top menu)
2. Click **Generate API Key**
3. Copy the long alphanumeric string

That's it. No sender ID registration, no DLT templates, no 48-hour approval wait.

## 3. Put it in `.env`

```
FAST2SMS_API_KEY=YOUR_LONG_KEY_HERE
```

Then:
```powershell
.\dev.ps1 clear
```

Done. The next subscription attempt sends a real SMS.

## 4. Verify it works

1. Go to `/subscribe/officer` with a real mobile number
2. Submit the form
3. SMS arrives within 3–5 seconds, sent from Fast2SMS's shared sender ID
4. OTP matches what the email shows

## Routes used

Fast2SMS has three SMS "routes":

| Route | Use for | DLT needed? | Cost |
|---|---|---|---|
| `otp` | OTPs (what we use for subscribers) | No, handled by Fast2SMS | ~₹0.10 |
| `q` | Quick transactional (our `send()` method for order updates etc.) | No | ~₹0.15 |
| `dlt` | DLT-compliant transactional with your own sender ID | Yes | ~₹0.14 |

Our `SmsService::sendOtp()` uses the `otp` route; `send()` uses `q`.

## Dev mode (no API key)

If `FAST2SMS_API_KEY` is empty, SMS is **written to `storage/logs/laravel.log`** instead of sent:

```
SMS (dev): OTP to 9988776655 => 452189
```

No cost, no account needed. Perfect for local testing.

## Cost estimates

| Monthly OTPs | Cost (@ ₹0.10 each) |
|---|---|
| 500 | ₹50 |
| 1,000 | ₹100 |
| 5,000 | ₹500 |
| 20,000 | ₹2,000 |

Transactional SMS (order updates via `send()`) costs ~₹0.15 each.

## Troubleshooting

- **"Invalid authorization"** → API key wrong or not pasted fully. Regenerate on Fast2SMS dashboard and re-copy.
- **"Invalid number"** → Fast2SMS requires a **10-digit Indian mobile without country code**. Our `SmsService::normalise()` strips `+91` and leading `0` automatically, but double-check what you're sending.
- **SMS not received** → Check Fast2SMS dashboard → **Reports** → delivery status. DND numbers may block even transactional SMS; try a non-DND test mobile first.
- **Low balance** → Add top-up on Fast2SMS dashboard. Minimum ₹100.

## Switching provider later

The `SmsService` interface (`sendOtp`, `send`) stays the same. To switch providers:

1. Replace the `$endpoint` URL and payload shape in `app/Services/SmsService.php`
2. Update the env var name and `config/services.php`

Controllers never change — they just call `$this->sms->sendOtp($mobile, $otp)`.
