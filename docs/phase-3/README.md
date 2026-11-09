# Phase 3 — Admin Panel: Core Modules

**Duration:** Weeks 3–4
**Payment milestone:** 30% on admin demo (~₹18,750)
**Status:** ✅ COMPLETE — ready for client demo

## What's built

### 11 Filament Resources

| # | Module | Resource | Pages | Key features |
|---|---|---|---|---|
| 1 | Orders | `OrderResource` | List + Edit | Status tabs (All/Pending/Successful/Rejected), pending-count sidebar badge, search by order#/mobile/name, inline mark-successful & mark-rejected, money column, **Download Excel** with optional status filter |
| 2 | Pages | `PageResource` | List + Create + Edit | Parent-child tree, slug auto-gen, rich-text editor, SEO section, publish/draft, sort |
| 3 | Books | `BookResource` | List + Create + Edit | Cover image upload, price + discount, stock |
| 4 | PDFs | `PdfResource` | List + Create + Edit | 20MB PDF upload, categories, download counter |
| 5 | Study Material | `StudyMaterialResource` | Manage (modal CRUD) | PDF upload, subject filter |
| 6 | Exam Names | `ExamResource` | Manage | Date, registration count join |
| 7 | Sliders | `SliderResource` | Manage | Image upload, drag-to-reorder |
| 8 | Newsline | `NewslineResource` | Manage | Start/end dates, drag-to-reorder |
| 9 | Reviews | `ReviewResource` | Manage | Star rating, inline publish action |
| 10 | Officer Subscribers | `OfficerSubscriberResource` | List + Create + Edit | 3 status tabs with counts, **3 Download Excel** buttons (subscribed/unverified/unsubscribed), inline **Resend OTP** on unverified |
| 11 | Soldier Subscribers | `SoldierSubscriberResource` | Same shape | Same features |
| 12 | Result Registrations | `ResultRegistrationResource` | List + Create + Edit | Filter by exam + zone, **Download Excel**, **Download Excel by zone** (exam + zone form), **Empty data** destructive action |

Note: 12 resources because Result Registrations is a separate module from the two Subscriber modules. Fits under the 11 modules the client asked for.

### Dashboard widget
`StatsOverview` — Orders today, Pending orders, Officer subs, Soldier subs.

### Excel export classes (`app/Exports/`)
- `OrdersExport` — all columns + optional status filter
- `OfficerSubscribersExport` — optional status filter
- `SoldierSubscribersExport` — optional status filter
- `ResultRegistrationsExport` — optional exam + zone filter

### Demo data factories (`database/factories/`)
Book, Page, Order, OfficerSubscriber, SoldierSubscriber, Exam, ResultRegistration, Review, Newsline.

### Demo seeder (`DemoDataSeeder`)
Seeded automatically unless `SEED_DEMO_DATA=false` in `.env`. Creates:
- 5 Pages (with parent-child)
- 12 Books
- 4 Exams
- 60 Result Registrations
- 40 Officer Subscribers (mixed statuses)
- 40 Soldier Subscribers (mixed statuses)
- 30 Orders (mixed statuses)
- 4 Newsline entries
- 6 Reviews

## Navigation (sidebar grouping)

- **Commerce** → Orders
- **Content** → Pages, Sliders, Newsline, Reviews
- **Library** → Books, PDFs, Study Material, Exam Names
- **Subscribers** → Officer Subscribers, Soldier Subscribers, Result Registrations

## To see everything

```powershell
git pull origin claude/optimistic-clarke-0ipamg
php artisan migrate:fresh --seed
php artisan optimize:clear
```

Refresh `http://army.test/admin`. Sidebar: 4 groups, 12 menu items. Each list page populated with demo data.

### Demo walkthrough script

1. **Dashboard** → stats widget with real numbers
2. **Orders** → see status tabs, click "Download Excel", open an order, mark it successful
3. **Pages** → see parent-child hierarchy, click Add page, create one
4. **Officer Subscribers** → see 3 tabs, click "Download subscribed", find an unverified row → "Resend OTP"
5. **Result Registrations** → click "Download Excel by zone" → pick a zone → file downloads
6. **Sliders** → upload an image, drag to reorder
7. **Books** → add a new book with cover image
8. **PDFs** → upload a PDF, watch size auto-captured

## Done. Phase 4 (public frontend) is next.
