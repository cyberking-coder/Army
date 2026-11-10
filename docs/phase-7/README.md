# Phase 7 — Data Migration from Old Site

**Duration:** Week 8 (parallel with Phase 6)
**Status:** ✅ COMPLETE — importer + scraper + CSV templates ready

## What's built

### 1. CSV importer: `php artisan legacy:import`

Reads a CSV file from `storage/migration/` and upserts rows into the new database. Idempotent — safe to re-run if you fix a row and want to retry.

```powershell
php artisan legacy:import {type} {file} [--dry-run]
```

Types supported:
- `pages` — hierarchical, resolves parent/child via `parent_slug`
- `books` — catalog with cover (upload cover image separately via admin)
- `pdfs` — library (upload actual PDF file separately)
- `exams` — master list
- `officers` — Officer Subscribers
- `soldiers` — Soldier Subscribers
- `orders` — legacy orders (marked successful by default)
- `registrations` — exam result signups (needs exam_slug to match)
- `redirects` — old URL → new URL mappings

**`--dry-run`** reads and validates the CSV, prints a count, but rolls back the transaction. Use it to preview before committing.

### 2. Site scraper: `php artisan legacy:scrape`

Fetches the old jointerritorialarmy.com HTML, extracts the `<h1>` title and `<main>` content, and upserts pages as **drafts** so the admin can review before publishing.

```powershell
php artisan legacy:scrape --url=https://www.jointerritorialarmy.com/join-as-jawan
php artisan legacy:scrape --base=https://www.jointerritorialarmy.com --only=join-as-jawan --only=join-as-officer --only=syllabus
php artisan legacy:scrape --url=... --dry-run
```

Strips `<script>`, `<style>`, `<nav>`, `<header>`, `<footer>` tags so only the body content lands in the page.

### 3. CSV templates

`storage/migration/` ships with sample CSVs for every type. They're committed so you have a template; replace the content with the real export from the old admin.

```
storage/migration/
├── pages.csv
├── books.csv
├── pdfs.csv
├── exams.csv
├── officers.csv
├── soldiers.csv
├── orders.csv
├── registrations.csv
└── redirects.csv
```

Any other file placed in this folder is `.gitignore`d (so real data with real emails doesn't get committed to GitHub).

## Where to get the source data

The old site is still PHP/MySQL. Ask the client for one of these:

### Option A — Full MySQL dump (best)
```
mysqldump -u root -p old_territorial_army > old.sql
```
Open in MySQL Workbench or DBeaver. Export each relevant table to CSV with the column names matching our template. Import.

### Option B — Export via existing admin
If the old admin has "Download Excel" buttons (and we know it does — the screenshots confirmed), export each list to Excel, save as CSV, massage column headers to match our templates, import.

### Option C — Scrape the live site (pages only)
Content pages are visible on <https://www.jointerritorialarmy.com> — use `legacy:scrape` to pull them. Subscribers / orders / PDF files have to come from Option A or B.

## Suggested migration order

Run these in order (each depends on the previous):

```powershell
# 1. Static content — scrape or import
php artisan legacy:scrape --base=https://www.jointerritorialarmy.com --only=join-as-jawan --only=join-as-officer
# OR
php artisan legacy:import pages storage/migration/pages.csv

# 2. Exams (needed by registrations)
php artisan legacy:import exams storage/migration/exams.csv

# 3. Catalog
php artisan legacy:import books storage/migration/books.csv
php artisan legacy:import pdfs storage/migration/pdfs.csv

# 4. People
php artisan legacy:import officers storage/migration/officers.csv
php artisan legacy:import soldiers storage/migration/soldiers.csv
php artisan legacy:import registrations storage/migration/registrations.csv

# 5. Transactional history
php artisan legacy:import orders storage/migration/orders.csv

# 6. SEO — old URLs → new URLs
php artisan legacy:import redirects storage/migration/redirects.csv
```

## File uploads (PDFs, book covers, slider images)

The importer only stores the `file_path` as a string — it does not upload the actual files. For each real migration:

1. Download every PDF / image from the old server (SFTP)
2. Upload to the new site's storage:
   - Local dev: drop files into `storage/app/public/pdfs/`, `storage/app/public/books/covers/`
   - Production: `aws s3 sync local/pdfs s3://territorial-army/pdfs` or the Cloudflare R2 equivalent
3. The paths in your CSV should match where you put them: `pdfs/tac-2025.pdf` etc.

## Verifying a successful migration

Row counts must match the source:
```powershell
# In SQLite (dev)
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM officer_subscribers;"
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM orders;"
```

Spot-check: open `/admin` → pick 5 random rows → open on the old site in another tab → confirm name, email, mobile, status all match.

## Redirects — why they matter

Once DNS points to the new site, requests for old URLs like `/sy_vyavasthaapak/add_page` will 404 unless redirected. `redirects.csv` handles this:

```csv
from_path,to_path,status_code
/old-contact,/contact,301
```

Our `routes/web.php` fallback (built in Phase 2) already consults the `redirects` table on every 404. No extra setup — just populate the CSV and import.

## Rollback

Everything the importer touches lives in SQL. To wipe everything imported and start fresh:

```powershell
.\dev.ps1 fresh
```

That wipes the DB and reseeds demo data. Then re-run the importers.

## Known limits

- Order items are **not** imported (the orders CSV has totals but not per-book line items). If the legacy orders table has line items too, we'd add a second importer.
- Images/PDFs aren't downloaded — the importer trusts the `file_path` in the CSV. Add a `php artisan legacy:fetch-files` helper later if volumes are large.
- The scraper is a best-effort HTML parser; if the old site uses heavy JS to render content, use Option A instead.

## Next: Phase 8 — testing & QA
