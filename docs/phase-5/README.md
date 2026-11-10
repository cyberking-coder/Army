# Phase 5 — Payments & Order Flow (CCAvenue)

**Duration:** Week 7
**Status:** ✅ COMPLETE — end-to-end flow live, with a dev fallback when CCAvenue isn't configured

## Payment gateway: **CCAvenue**

CCAvenue uses a **form-POST redirect** flow (not a JS popup):

1. Customer completes checkout form on our site
2. We build a request string and encrypt it with AES-128-CBC using the merchant's working key
3. Form auto-submits to CCAvenue's hosted payment page
4. User pays on CCAvenue
5. CCAvenue redirects back to our `redirect_url` with an encrypted `encResp` field
6. We decrypt, read `order_status`, mark the order

## What's built

### Services
- **`App\Services\Cart`** — session-backed shopping cart (unchanged)
- **`App\Services\CCAvenueService`** — AES-128-CBC encrypt/decrypt + CCAvenue URL builder
  - Uses the fixed IV from CCAvenue's integration kit
  - Encryption key: `hex2bin(md5(working_key))`
  - `buildEncryptedRequest(array)` → hex-encoded ciphertext for `encRequest`
  - `decodeResponse(string encResp)` → parsed response array

### Controllers
- **`CartController`** — add/update/remove/index (unchanged)
- **`CheckoutController`**:
  - `show()` — render shipping form
  - `place()` — validate, write `orders` + `order_items` in a transaction, build encrypted CCAvenue request, show the auto-submitting redirect page
  - `ccavenueReturn()` — decrypts `encResp`, marks the order Successful/Rejected based on `order_status`
  - `devMarkPaid()` — dev fallback when CCAvenue env vars are empty
  - `orderShow()` — confirmation page

### Routes (new)

```
GET    /cart
POST   /cart/add/{book}
PATCH  /cart/update/{book}
DELETE /cart/remove/{book}

GET    /checkout
POST   /checkout                      → place order, show pay page
POST   /checkout/ccavenue/return      → CCAvenue redirects here (CSRF-exempt)
POST   /checkout/{order}/dev-paid     → dev fallback
GET    /order/{order}                 → confirmation page
```

### Views
- `public/cart/index.blade.php`
- `public/checkout/index.blade.php` — shipping form
- `public/checkout/pay.blade.php` — auto-submitting form to CCAvenue OR "Simulate Payment" dev button
- `public/order/show.blade.php`

### Header
Cart icon with saffron count badge (unchanged).

## Dev workflow (no CCAvenue keys yet)

1. Add a book to cart → `/cart` → Proceed to Checkout
2. Fill shipping form → **Pay with Razorpay** button → actually goes to CCAvenue intro page
3. Yellow banner: "Dev mode — CCAvenue not set. Click to simulate."
4. Simulate → order marked Successful → green confirmation page
5. Admin → Orders → Successful tab → see the new order

## Going live with CCAvenue

Fill in `.env`:
```
CCAVENUE_MERCHANT_ID=1234567
CCAVENUE_ACCESS_CODE=AVXXXXXXXXXXXXXXXXXX
CCAVENUE_WORKING_KEY=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
CCAVENUE_BASE_URL=https://test.ccavenue.com
```

Then `.\dev.ps1 clear` and real CCAvenue takes over.

### Getting credentials from CCAvenue
1. Sign up at <https://world.ccavenue.com> and complete KYC
2. CCAvenue dashboard → Settings → **API Keys**
3. Copy: **Merchant ID**, **Access Code**, **Working Key**
4. Add our return URL `https://yoursite.com/checkout/ccavenue/return` to your merchant profile's allowed redirect URLs

### Test vs Live
- `CCAVENUE_BASE_URL=https://test.ccavenue.com` for sandbox
- `CCAVENUE_BASE_URL=https://secure.ccavenue.com` for production

Test credentials and test card numbers are issued separately from CCAvenue during onboarding.

## Order lifecycle
```
Cart → Checkout form → place() → Order created (pending)
                              ↓
                encRequest built and form posted to CCAvenue
                              ↓
                User pays on CCAvenue hosted page
                              ↓
          CCAvenue POSTs encResp back to /checkout/ccavenue/return
                              ↓
          Decrypt → check order_status
            ├─ Success → markSuccessful(tracking_id) → confirmation page
            └─ Failure/Aborted → markRejected(reason) → status page
```

## Why no webhook?
CCAvenue's integration is synchronous — the redirect back to `redirect_url` IS the notification. There's no separate webhook/server-to-server callback like Razorpay's. The one safety net CCAvenue offers is the dashboard Status Inquiry API (not implemented here; add if orders ever get stuck).

## Known limits
- No stock decrement on successful order (one line in markSuccessful if desired)
- No email confirmation yet → Phase 6
- No partial refunds flow (admin marks Rejected for manual refund via CCAvenue dashboard)
