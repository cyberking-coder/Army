# Phase 2 — Backend Foundation

**Duration:** Week 2
**Status:** Scaffolded — ready for `composer install` and `php artisan migrate`

## What's in this phase
Everything a developer needs to boot the Laravel + Filament backend with the database structure matching Phase 1's schema.

## Run on local / dev server

```bash
composer install
cp .env.example .env
php artisan key:generate
mysql -u root -e "CREATE DATABASE territorial_army CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
# edit .env with your DB creds + admin seed vars
php artisan migrate --seed
php artisan filament:install --panels
php artisan serve
```

Open <http://localhost:8000/admin> and log in with the credentials you set in `.env`.

### If PHP isn't installed locally
Pick one:
- **Laravel Herd** (easiest, Mac/Windows): <https://herd.laravel.com>
- **XAMPP** (Windows/Mac/Linux): <https://www.apachefriends.org>
- **Docker** — ask and I'll add a `docker-compose.yml`

## Phase 2 checklist
- [x] Laravel 11 composer.json
- [x] `.env.example` with all keys
- [x] 15 migrations (admins, pages, sliders, newsline, reviews, pdfs, study_material, books, exams, officer_subscribers, soldier_subscribers, result_registrations, orders+items, settings+redirects, activity_log+jobs+cache)
- [x] 17 Eloquent models
- [x] Filament AdminPanelProvider (purple theme at `/admin`)
- [x] Admin auth config (admin guard, not user guard)
- [x] Cloudflare R2 disk config
- [x] Super-admin + default settings seeder
- [x] Placeholder home view
- [x] Legacy-URL redirect fallback
- [ ] `composer install` run
- [ ] `php artisan migrate --seed` run
- [ ] First admin login verified at `/admin`

## Not in Phase 2 (comes later)
- Filament Resource classes for the 11 modules → Phase 3
- Public-site frontend views → Phase 4
- Razorpay → Phase 5
- Email / OTP → Phase 6

## Next (Phase 3)
Build Filament resources for every table: OrderResource, PageResource, SliderResource, NewslineResource, ReviewResource, PdfResource, StudyMaterialResource, BookResource, ExamResource, OfficerSubscriberResource, SoldierSubscriberResource, ResultRegistrationResource — each with table columns, filters, search, Excel export, forms, and status badges.
