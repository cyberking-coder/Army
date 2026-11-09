# Territorial Army Website & Admin Panel

Complete rebuild of the Territorial Army website (reference: jointerritorialarmy.com) with a fully-featured custom admin panel for non-technical administrators.

Reference screenshots of the target admin UI are in [`docs/reference/`](./docs/reference/).

---

## Project Summary

| Item | Value |
|---|---|
| **Project cost** | ₹60,000 – ₹65,000 (one-time) |
| **Timeline** | ~10 weeks (2.5 months) |
| **Deliverable** | Custom website + admin panel + source code + 30 days support |
| **Monthly running cost** | ₹1,100 – ₹3,800 (hosting, storage, email) |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Public Website | Laravel 11 + Blade + Tailwind CSS + Alpine.js |
| Admin Panel | Filament v3 |
| Backend | Laravel 11 (PHP 8.3) |
| Database | MySQL 8 |
| File Storage | Cloudflare R2 |
| Payments | Razorpay |
| Email | Resend / AWS SES |
| Hosting | VPS (Hostinger / Hetzner) |
| CDN & Security | Cloudflare |

---

## Admin Panel Modules

1. **Orders** – pending / successful / rejected, Excel export
2. **Pages** – dynamic page builder with parent/menu/description
3. **Result Registration** – exam signup data with zone filter, Excel export
4. **Officer Subscribers** – verified / unverified / unsubscribed
5. **Soldier Subscribers** – verified / unverified / unsubscribed
6. **PDFs** – PDF library upload & management
7. **Study Material** – study material files library
8. **Books** – books catalog
9. **Exam Names** – exam master list
10. **Slider** – homepage banner management
11. **Newsline & Reviews** – announcements + review moderation

> Removed from scope per client: Consignment CSV, Coupons, Exam Center, Move to 100% Officers.

---

## Project Phases

### Phase 1 — Discovery & Planning  *(Week 1)*  ✅
Scope, sitemap, database schema, wireframes, brand & design system.
See [`docs/phase-1/`](./docs/phase-1/).

### Phase 2 — Backend Foundation  *(Week 2)*  ✅
Laravel 11 project setup, all migrations, Eloquent models, Filament v3 install, admin auth.
See [`docs/phase-2/`](./docs/phase-2/).

### Phase 3 — Admin Panel: Core Modules  *(Weeks 3–4)*
Filament resources for all 11 modules.

### Phase 4 — Public Website Frontend  *(Weeks 5–6)*
Homepage, dynamic pages, PDF library, books catalog, subscription forms.

### Phase 5 — Payments & Order Flow  *(Week 7)*
Razorpay integration, cart, checkout, order lifecycle.

### Phase 6 — Email & Automation  *(Week 8)*
OTP, email templates, queued jobs, newsletter export.

### Phase 7 — Data Migration  *(Week 8, parallel)*
Move existing data from old site to new system.

### Phase 8 — Testing & QA  *(Week 9)*
Cross-browser, mobile, security, Lighthouse.

### Phase 9 — Deployment & Handover  *(Week 10)*
VPS, SSL, Cloudflare, admin training, source code handover.

---

## Payment Schedule

| Milestone | % | Amount (at ₹62,500) |
|---|---|---|
| Project kickoff | 40% | ₹25,000 |
| Admin panel demo (end of week 4) | 30% | ₹18,750 |
| Final delivery & handover | 30% | ₹18,750 |

---

## Getting Started (for Developers)

```bash
composer install
cp .env.example .env
php artisan key:generate
mysql -u root -e "CREATE DATABASE territorial_army CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
# edit .env with DB creds + admin seed vars
php artisan migrate --seed
php artisan filament:install --panels
php artisan serve
```

Then open http://localhost:8000/admin.

## Repository Structure

```
.
├── app/
│   ├── Filament/           # Admin panel resources
│   ├── Models/             # Eloquent models
│   └── Providers/Filament/ # AdminPanelProvider
├── config/
│   ├── auth.php
│   └── filesystems.php
├── database/
│   ├── migrations/
│   └── seeders/
├── docs/
│   ├── reference/          # Reference screenshots
│   ├── phase-1/            # Discovery & planning
│   └── phase-2/            # Backend foundation
├── resources/views/public/ # Public-site Blade templates
├── routes/web.php
├── composer.json
└── .env.example
```
