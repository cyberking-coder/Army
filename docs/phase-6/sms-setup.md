# SMS Setup — MSG91

Transactional SMS in India requires DLT (Distributed Ledger Technology) registration. This is enforced by TRAI since 2020. One-time setup; after that SMS just works.

## 1. Sign up for MSG91

<https://msg91.com/signup>

Add ₹100-500 as starting balance — enough for ~2,000-5,000 SMS.

**Cost:** roughly **₹0.15–₹0.25 per SMS** for transactional messages.

## 2. DLT registration (one-time)

a. In MSG91 dashboard → **DLT** → register your business entity with any of the Indian telecom registries (Jio, Airtel, Vi, BSNL — pick one; MSG91 guides you)

b. Register a **Sender ID** (6 characters, all caps, e.g. `TRARMY`)

c. Register an **OTP template** on the same DLT portal. Template must match exactly what gets sent. Example:
> `##OTP## is your Territorial Army verification code. Valid for 15 minutes. Do not share.`

The `##OTP##` is the variable placeholder DLT expects. MSG91 will approve + give you a **template id** once registered.

## 3. Get your credentials

MSG91 dashboard → **API settings** → copy:
- **Auth key** (the main secret)
- **Sender ID** (what you registered, e.g. TRARMY)
- **OTP Template ID** (the DLT-approved template)

## 4. Put them in `.env`

```
MSG91_AUTH_KEY=123456ABCDxxxxxxxxxxxxxxxx
MSG91_SENDER_ID=TRARMY
MSG91_OTP_TEMPLATE_ID=65xxxxxxxxxxxxxx
```

Then:
```powershell
.\dev.ps1 clear
```

Done. The next subscription attempt sends a real SMS.

## 5. Verify it works

1. Go to `/subscribe/officer` with a real mobile number
2. Submit the form
3. SMS arrives within seconds
4. OTP matches what the email shows

## Dev mode (no MSG91 key)

If any of the three env vars is empty, SMS is **written to `storage/logs/laravel.log`** instead of sent. Open with `.\dev.ps1 logs`:

```
SMS (dev): OTP to 919988776655 => 452189
```

No cost, no account needed — perfect for local testing.

## Troubleshooting

- **MSG91 returns "Invalid template"** → DLT template still pending approval; wait 24h
- **"Sender ID not registered"** → DLT sender ID not yet approved; MSG91 dashboard shows approval status
- **SMS not received** → check MSG91 dashboard → **Reports** for delivery status. DND numbers block transactional SMS unless you also register as a "Service Explicit" sender.
- **₹ cost higher than expected** → promotional SMS is cheaper (₹0.10) but can't send OTPs; transactional costs more but has 100% delivery to DND numbers.

## Alternatives if MSG91 doesn't work out

Same architecture, different provider — swap the `SmsService` endpoint:

- **Fast2SMS** — cheapest, no DLT needed for OTP route, less reliable to DND
- **Twilio** — global, ~4× more expensive in India
- **Gupshup** — enterprise, more features
- **TextLocal** — simpler onboarding, slightly more expensive

Changing provider is a 20-line edit to `app/Services/SmsService.php` — keep the same `sendOtp()` method signature and controllers don't change.
