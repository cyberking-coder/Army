# Phase 3 — Admin Panel: Core Modules

**Duration:** Weeks 3–4
**Payment milestone:** 30% on admin demo (~₹18,750)
**Status:** All 11 modules scaffolded

## What's built

| # | Module | Resource class | Pages | Features |
|---|---|---|---|---|
| 1 | Orders | `OrderResource` | List (with status tabs + badge count), Edit | Search by order#/mobile/name, filter by status, change-status actions (mark successful / rejected), money column, pending-badge in sidebar |
| 2 | Pages | `PageResource` | List, Create, Edit | Parent-child, slug auto-gen, rich text editor, status badge, SEO section (collapsed), sort |
| 3 | Books | `BookResource` | List, Create, Edit | Cover image upload, price + discount, stock, active toggle |
| 4 | PDFs | `PdfResource` | List, Create, Edit | PDF upload (20MB), categories, download count, auto file-size capture |
| 5 | Study Material | `StudyMaterialResource` | Manage (modal CRUD) | PDF upload, subject filter |
| 6 | Exam Names | `ExamResource` | Manage | Date, registration count (counted join) |
| 7 | Sliders | `SliderResource` | Manage | Image upload, drag-to-reorder, active toggle |
| 8 | Newsline | `NewslineResource` | Manage | Start/end dates, drag-to-reorder |
| 9 | Reviews | `ReviewResource` | Manage | Star rating, publish toggle, inline publish action |
| 10 | Officer Subscribers | `OfficerSubscriberResource` | List (3 tabs), Create, Edit | Tabs: Subscribed / Unverified / Unsubscribed with counts |
| 11 | Soldier Subscribers | `SoldierSubscriberResource` | Same shape | Same tabs |
| 12 | Result Registrations | `ResultRegistrationResource` | List, Create, Edit | Filter by exam + zone, roll-no search |

Plus `StatsOverview` dashboard widget: orders today, pending orders, officer subscribers, soldier subscribers.

## Navigation groups

- **Commerce**: Orders
- **Content**: Pages, Sliders, Newsline, Reviews
- **Library**: Books, PDFs, Study Material, Exam Names
- **Subscribers**: Officer Subscribers, Soldier Subscribers, Result Registrations

## Not yet (comes in later phases)

- Excel export action on list pages → Phase 3 polish (TODO below)
- Public-site forms that write to these tables → Phase 4
- Razorpay payment flow + public Order placement → Phase 5
- OTP email flow for subscribers → Phase 6

## TODO before Phase 4

- [ ] Add Excel export action to Orders, Subscribers, Result Registrations (matches "Download excel" button in reference screenshots)
- [ ] Add zone-filtered Excel download on Result Registrations (reference's "Download excel by zone")
- [ ] Verify all forms render correctly after `php artisan optimize:clear`
- [ ] Add a "Send OTP again" action to Unverified subscribers
- [ ] Add factories + seeders for demo data (so client demo isn't an empty panel)

## To view after pulling

```powershell
git pull origin claude/optimistic-clarke-0ipamg
php artisan optimize:clear
# refresh http://army.test/admin
```

Sidebar should now show 4 groups with 12 menu items. Click each to confirm the table loads.
