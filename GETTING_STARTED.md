# Getting Started — Developer Quick Reference

The only doc you need. One page, no jargon.

---

## Daily workflow (99% of the time)

```powershell
cd D:\army_website\army
.\dev.ps1 start
```

Opens the public site and admin in your browser. Herd serves it automatically — you don't need `php artisan serve`.

Edit any Blade view or PHP file → **refresh the browser**. That's it.

---

## When you pulled new code from GitHub

```powershell
.\dev.ps1 pull
```

Runs `git pull` → `composer install` → `php artisan migrate` → clears caches. Three minutes of a coffee break.

---

## When the admin panel looks weird or changes don't show

```powershell
.\dev.ps1 clear
```

Clears Laravel's cache. Takes 2 seconds.

---

## When demo data is messy and you want a fresh start

```powershell
.\dev.ps1 fresh
```

Wipes the SQLite database, re-runs all migrations, reseeds demo data (12 books, 40 subscribers, 30 orders, etc.).

⚠️ **Destroys all data.** Only in dev.

---

## When you forgot the admin password

```powershell
.\dev.ps1 admin
```

Asks for email + new password. Resets or creates the account.

---

## When something is broken and you want the error

```powershell
.\dev.ps1 logs
```

Tails `storage/logs/laravel.log`. Press Ctrl+C to stop.

---

## First-time setup (already done — reference only)

```powershell
cd D:\army_website\army
composer install
copy .env.example .env
php artisan key:generate
New-Item -ItemType File -Path "database\database.sqlite"
# edit .env: set DB_CONNECTION=sqlite, ADMIN_EMAIL, ADMIN_PASSWORD
php artisan migrate --seed
php artisan filament:install --panels
```

Then in Herd: Add Site → point to `D:\army_website\army` → PHP 8.4 → HTTPS off.

---

## URLs

- **Public:** http://army.test
- **Admin:** http://army.test/admin
- **GitHub:** https://github.com/cyberking-coder/Army on branch `claude/optimistic-clarke-0ipamg`

---

## Where things live

```
app/
├── Models/              → Database models (rarely touched)
├── Filament/Resources/  → Admin panel pages (edit here for admin changes)
├── Http/Controllers/    → Public-site logic
└── Exports/             → Excel export definitions

resources/views/
├── layouts/public.blade.php  → Site header + footer
└── public/                   → All public-facing pages
    ├── home.blade.php        → Homepage
    ├── page.blade.php        → Dynamic admin-created pages
    └── ...

routes/web.php           → All public URLs
database/migrations/     → DB schema (change via a new migration)
.env                     → Config, secrets, admin password
```

---

## Common tasks

### "Change the homepage design"
Edit `resources/views/public/home.blade.php`. Refresh browser.

### "Change the admin panel's brand color"
Edit `app/Providers/Filament/AdminPanelProvider.php` → `'primary' => Color::hex('#XXXXXX')`.

### "Add a new field to a model"
Create a migration: `php artisan make:migration add_field_to_books_table` → edit the up/down → `php artisan migrate`. Then add the field to the Filament resource's `form()` and `table()`.

### "Add a new menu item to the admin sidebar"
Create a new Resource: `php artisan make:filament-resource ThingName --generate`.

---

## Client docs

Give `CLIENT_MANUAL.md` to the client. It's written for a non-technical admin — walks them through every common task with zero jargon.

---

## When something breaks beyond you

Post the error here. Don't fight it alone — paste the error, we fix it in one commit.
