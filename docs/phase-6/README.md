# Phase 6 — Email &amp; Automation

**Duration:** Week 8
**Status:** ✅ COMPLETE — all emails queued, rate limits in place

## What's built

### Mailables (`app/Mail/`)
- **`OtpMail`** — 6-digit verification code for officer/soldier subscribers
- **`OrderConfirmationMail`** — sent when an order flips from `pending` to `successful`
- **`OrderStatusMail`** — sent for any other status change (e.g. shipped, rejected)

All use `Mail::to($email)->queue(new …)` — they go to the queue, not sent inline. The browser isn't blocked waiting for SMTP.

### Email templates (`resources/views/emails/`)
Shared layout (`emails/layout.blade.php`) matches the site branding: army-green header, gold accents, clean white card. Mobile-friendly, inline CSS (email-safe).

- `emails/otp.blade.php` — big monospace code, 15-min expiry notice
- `emails/order-confirmation.blade.php` — items table, shipping address, "View order" button
- `emails/order-status.blade.php` — old → new status, consignment number if set

### Order status auto-emailing (`app/Observers/OrderObserver.php`)
Fires on every `Order::updated` event. If `status` changed:
- `pending → successful` → OrderConfirmationMail
- Any other transition → OrderStatusMail (with old + new status)

Registered in `AppServiceProvider::boot()`. **The admin never has to think about emails** — change status in Filament, email goes out.

### Subscriber OTP flow (wired for real)
`SubscriptionController` no longer flashes the OTP to the screen. It:
1. Generates a 6-digit OTP + 15-min expiry
2. Assigns a 48-char unsubscribe token (unique across both subscriber tables)
3. Queues `OtpMail` to the subscriber
4. Flashes "Verification code sent to your@email" to the UI
5. Verify page validates the OTP as before

### Unsubscribe link
Route: `GET /unsubscribe/{token}`
Looks up the token in both subscriber tables, marks `status=unsubscribed`, shows a friendly confirmation page. **Can be included in every marketing email** — just interpolate the subscriber's `unsubscribe_token`.

### Rate limiting
New `RateLimiter::for('public-forms')` — **5 requests per minute per IP**. Applied to:
- `POST /subscribe/officer`
- `POST /subscribe/soldier`
- `POST /exams/{slug}/register`

Stops bots from spamming the forms.

### Migration
`add_unsubscribe_token_to_subscribers` adds `unsubscribe_token` (unique, nullable) to both `officer_subscribers` and `soldier_subscribers`.

## Mail driver — dev vs production

### Dev (default, zero setup)
`.env` → `MAIL_MAILER=log` → emails are **written to `storage/logs/laravel.log`** as plain text. Open with `.\dev.ps1 logs` to see them. No SMTP needed.

### Production options

**SMTP** (Gmail, Hostinger mail, any provider):
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=you@yourdomain.com
MAIL_PASSWORD=secret
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@jointerritorialarmy.com
```

**AWS SES** (cheaper at scale):
```
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=xxx
AWS_SECRET_ACCESS_KEY=xxx
AWS_DEFAULT_REGION=ap-south-1
```

## Queue — dev vs production

### Dev
`QUEUE_CONNECTION=database` is already in `.env.example`. Emails sit in the `jobs` table until a worker picks them up.

To actually send them in dev:
```powershell
php artisan queue:work
```
Keep that running in a second terminal. Or set `QUEUE_CONNECTION=sync` in `.env` to send immediately (slower but no extra process).

### Production (on Hostinger VPS)
Run `php artisan queue:work --sleep=3 --tries=3 --max-time=3600` under **Supervisor** (auto-restarts on crash). One-time setup; deploy once, then forget.

## To apply on your machine

```powershell
.\dev.ps1 pull
.\dev.ps1 fresh   # adds the unsubscribe_token column
```

## Try it

1. Go to `/subscribe/officer` → fill form → submit
2. Blue banner: "Verification code sent to your@email"
3. Run `.\dev.ps1 logs` — see the email body including the 6-digit OTP
4. Paste the OTP on the verify page → ✅ "Verified!"

For order flow:
1. Place a test order (dev mode, "Simulate Payment Success")
2. Open `.\dev.ps1 logs` — see the order confirmation email
3. In admin, change the order status to Rejected → check logs again for the status-change email

## What's still TODO (Phase 8 polish)
- Contact form that emails admin (easy, 5 lines)
- Email templates for "OTP resent from admin" (currently just regenerates the code, doesn't re-send email)
- Daily digest email to admin with new orders + subscribers (nice-to-have)

## Next: Phase 7 — data migration from the old site
