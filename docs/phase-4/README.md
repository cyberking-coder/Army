# Phase 4 — Public Website Frontend

**Duration:** Weeks 5–6
**Status:** ✅ COMPLETE — all public-facing pages built

## What's built

### Layout (`resources/views/layouts/public.blade.php`)
- Tailwind CDN + Alpine.js (zero npm build needed for dev)
- Google Fonts: Poppins (display) + Inter (body)
- Brand purple color palette (`brand-50` to `brand-900`)
- Sticky header with auto-generated nav (from `pages` table, cached 5 min)
- Mobile hamburger menu (Alpine-powered)
- Flash messages (`success`, `otp_sent`)
- Footer with 4-column layout + copyright

### Auto-generated navigation (`MenuComposer`)
Reads root-level published pages and their published children. Every admin-created page with `parent_id=null` becomes a top-nav item; children become dropdown items. Cache cleared automatically; admin can also hit `php artisan cache:clear`.

### Pages built

| Route | View | Purpose |
|---|---|---|
| `GET /` | `public.home` | Slider + newsline ticker + quick links + 4 latest books + 3 reviews + CTA to subscribe |
| `GET /{slug}` | `public.page` | Any admin-created page (parent or child), with related children listed |
| `GET /books` | `public.books.index` | Grid of 12 per page, pagination |
| `GET /books/{slug}` | `public.books.show` | Detail + Buy button (wired in Phase 5) |
| `GET /pdfs` | `public.pdfs.index` | Downloadable PDF list with counter |
| `GET /pdfs/{slug}/download` | (streams file) | Increments `downloads_count`, serves file |
| `GET /study-material` | `public.study-material.index` | Subject-tagged file list |
| `GET /exams` | `public.exam.index` | Active exams list |
| `GET /exams/{slug}/register` | `public.exam.register` | Result registration form → writes to `result_registrations` |
| `GET /subscribe/officer` | `public.subscribe.officer` | Officer subscription form |
| `GET /subscribe/soldier` | `public.subscribe.soldier` | Soldier subscription form |
| `GET /verify-otp/{type}/{id}` | `public.subscribe.verify` | 6-digit OTP entry, verifies and flips `status` to `subscribed` |
| `GET /reviews` | `public.reviews` | Published reviews grid |
| `GET /newsline` | `public.newsline` | Announcements list |
| `GET /about` · `/contact` · `/privacy` · `/terms` | static | Placeholders |

### OTP flow (demo mode)
Form → generate 6-digit OTP → flash the OTP to the next page (yellow banner) so you can test the flow without email. Phase 6 wires the actual email send; the controller logic stays the same.

### Legacy URL redirect fallback
`routes/web.php` keeps the fallback that checks the `redirects` table for 301 matches. Used by Phase 7 data migration.

## Not yet (Phase 5+)

- Cart + Razorpay checkout → Phase 5
- Order confirmation page → Phase 5
- Real OTP email delivery → Phase 6
- Contact form → Phase 6
- `sitemap.xml` + robots meta → Phase 8 polish

## To see it

```powershell
git pull origin claude/optimistic-clarke-0ipamg
php artisan optimize:clear
```

Refresh `http://army.test`. You should see:

- Homepage with purple hero (if sliders exist — upload one in admin) or fallback hero
- Newsline ticker scrolling
- 4 book cards from demo data
- 3 review cards
- Working nav with dynamic pages (e.g. "Join as Officer" dropdown with children)

### Click-through smoke test

1. `/` → scrolls, newsline animates
2. `/books` → grid, click a book → detail page with Buy button (Phase 5)
3. `/pdfs` → list (empty unless admin uploaded PDFs via admin)
4. `/exams` → list → click Register → fill form → success
5. `/subscribe/officer` → fill form → see "OTP (demo): 123456" banner → paste on next page → verified
6. `/{some-page-slug}` → opens admin-created page content
7. Nav dropdown on hover (desktop) / hamburger (mobile)

## Known rough edges

- Tailwind CDN warning in browser console — fine for dev; swap to compiled CSS in Phase 8 production build
- No image CDN yet; `/storage/*` serves locally. R2 kicks in once .env sets it as default disk.
- No search bar on books/pdfs listings — easy addition if client wants

## Next: Phase 5 — Payments & Order Flow
