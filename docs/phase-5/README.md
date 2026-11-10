# Phase 5 — Payments & Order Flow

**Duration:** Week 7
**Status:** ✅ COMPLETE — end-to-end flow live, with a dev fallback when Razorpay isn't configured

## What's built

### Services
- **`App\Services\Cart`** — session-backed shopping cart (`add`, `update`, `remove`, `clear`, `items`, `count`, `subtotal`, `isEmpty`). One cart per browser session.
- **`App\Services\RazorpayService`** — thin wrapper around the official `razorpay/razorpay` SDK:
  - `createOrder(Order)` — creates a Razorpay order, stores `razorpay_order_id` on the local Order
  - `verifyPayment(order_id, payment_id, signature)` — validates the HMAC signature returned from checkout
  - `verifyWebhook(payload, signature)` — validates webhook events

### Controllers
- **`CartController`** — `/cart` index, add, update, remove
- **`CheckoutController`** — `/checkout` form, place order (writes `orders` + `order_items`), show payment page, verify payment, dev-mode mark-paid
- **`WebhookController`** — `POST /webhooks/razorpay` for async events (`payment.captured`, `payment.failed`). CSRF excluded.

### Routes (new)

```
GET    /cart
POST   /cart/add/{book}
PATCH  /cart/update/{book}
DELETE /cart/remove/{book}

GET    /checkout
POST   /checkout                      → place order, show pay page
POST   /checkout/{order}/verify       → confirm payment, mark successful
POST   /checkout/{order}/dev-paid     → dev fallback (only when RAZORPAY_KEY empty)
GET    /order/{order}                 → confirmation page

POST   /webhooks/razorpay             → async events (no CSRF)
```

### Views
- `public/cart/index.blade.php` — line items, qty edit, remove, subtotal, Proceed to Checkout
- `public/checkout/index.blade.php` — shipping form + sticky order summary sidebar
- `public/checkout/pay.blade.php` — Razorpay JS SDK button if keys set, else "Simulate Payment" dev button
- `public/order/show.blade.php` — green/amber/red confirmation card based on status

### Header integration
Top nav shows a cart icon with a saffron badge showing current cart count (reads from session).

### Book detail page
Buy button replaced with **Add to Cart** form (qty input + button). Shows stock count.

## Dev workflow (no Razorpay keys yet)

1. Open any book → Add to Cart → `/cart` → Proceed to Checkout
2. Fill shipping form → click "Pay with Razorpay"
3. Yellow banner appears: "Dev mode — Razorpay not set. Click to simulate."
4. Click simulate → order marked successful → `/order/{id}` shows green confirmation

The admin Orders list immediately shows the new order under the **Successful** tab.

## When Razorpay keys are added

Fill in `.env`:
```
RAZORPAY_KEY=rzp_test_xxxxxxxxxxxxxxxx
RAZORPAY_SECRET=xxxxxxxxxxxxxxxxxxxxxxx
RAZORPAY_WEBHOOK_SECRET=xxxxxxxxxxxxxxxx
```

Then `.\dev.ps1 clear` and the real Razorpay checkout popup appears at `/checkout` → pay → verify → done.

### Razorpay webhook setup (production)
In Razorpay dashboard → Settings → Webhooks → Add:
- URL: `https://yoursite.com/webhooks/razorpay`
- Events: `payment.captured`, `payment.failed`
- Secret: copy into `RAZORPAY_WEBHOOK_SECRET`

## Order lifecycle
```
Cart → Checkout → place() → Order created (pending)
                          ↓
                  Razorpay order created, pay page shown
                          ↓
            User completes payment on Razorpay popup
                          ↓
              handler posts to /checkout/{order}/verify
                          ↓
               Signature verified → markSuccessful()
                          ↓
               Cart cleared → redirect to /order/{id}
                          ↓
                    Green confirmation page
```

Async safety net: if the browser dies between payment and verify, the webhook (`payment.captured`) still flips the order to successful.

## Known limits (polish in Phase 8)
- No email confirmation yet → wired in Phase 6
- No stock decrement on successful order (will need a `$book->decrement('stock')` hook)
- No partial refunds flow (admin can mark Rejected for manual refund)
- No GST line (project scope omits tax handling)

## To try

```powershell
.\dev.ps1 pull
.\dev.ps1 clear
```

Then:
1. `http://army.test/books` → pick one → **Add to Cart**
2. Cart icon in header shows a `1` badge
3. Click cart icon → `/cart` → **Proceed to Checkout**
4. Fill form → **Pay with Razorpay** → **Simulate Payment** → green confirmation
5. Open `/admin/orders` → Successful tab → see the new row
