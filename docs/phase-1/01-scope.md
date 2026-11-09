# Phase 1 — Scope Document

**Project:** Territorial Army Website & Admin Panel
**Reference:** jointerritorialarmy.com
**Budget:** ₹60,000 – ₹65,000
**Duration:** ~10 weeks

## 1. Scope — IN

### Public Website
- Homepage with hero slider, newsline ticker, featured sections
- Dynamic pages (admin can add unlimited) with parent-child menu hierarchy
- PDF library + download page
- Study material library
- Books catalog with buy flow
- Exam result registration (public form)
- Officer subscription (with OTP email verification)
- Soldier subscription (with OTP email verification)
- Reviews display
- About / Contact pages
- Mobile-responsive design
- SEO basics (meta, OG tags, sitemap.xml, robots.txt)

### Admin Panel (11 modules)
1. Orders — pending / successful / rejected + Excel export
2. Pages — hierarchical page builder
3. Result Registration — exam signup + zone filter + Excel export
4. Officer Subscribers — 3 lists + Excel export
5. Soldier Subscribers — 3 lists + Excel export
6. PDFs — upload / manage
7. Study Material — upload / manage
8. Books — catalog + pricing
9. Exam Names — master list
10. Slider — homepage banner images
11. Newsline & Reviews

### Supporting Features
- Razorpay payment gateway
- Email automation (OTP, order confirmations)
- Admin login + password change
- Cloudflare R2 file storage
- Data migration from existing site
- VPS deployment + SSL + CDN
- 1–2 hour admin training session
- 30 days of post-launch bug-fix support
- Full source code handover

## 2. Scope — OUT (per client request)
- Consignment CSV upload
- Coupons / discount codes
- Exam Center management
- "Move to 100% Officers" workflow

## 3. Scope — OUT (optional add-ons)
- Logo / brand design
- Content writing
- Mobile app
- Multi-language support
- WhatsApp Business API
- SEO campaign & ad setup
- Annual maintenance (AMC) beyond 30-day support

## 4. Assumptions
- Client provides all textual content and hi-res images / logo
- Client creates accounts for: Razorpay, Cloudflare, hosting, email service, domain
- Client migrates DNS to Cloudflare
- All admin users are trusted (single-tenant)
- Expected traffic: up to ~100K page views/month

## 5. Client Dependencies
| Needed | Due by |
|---|---|
| Final list of pages + copy | End of Week 1 |
| Logo files (SVG preferred) | End of Week 1 |
| List of PDFs / books to migrate | End of Week 2 |
| Razorpay KYC approval | End of Week 6 |
| Hosting provider chosen | End of Week 8 |
| DNS access | End of Week 9 |

## 6. Acceptance Criteria
1. All 11 admin modules function per reference screenshots
2. Public site works on Chrome, Firefox, Safari, Edge (desktop + mobile)
3. One end-to-end test order via live Razorpay
4. Admin can create a page, upload a PDF, add a slider — all live
5. Existing data migrated with row counts matching source
6. SSL certificate active (grade A on SSL Labs)
7. Admin training completed + user manual handed over
8. Source code in client's Git repository

## 7. Sign-off
| Party | Name | Date | Signature |
|---|---|---|---|
| Client | | | |
| Developer | | | |
