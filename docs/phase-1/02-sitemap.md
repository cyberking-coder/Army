# Phase 1 — Sitemap & URL Structure

## Public Website

```
/                                   Homepage (slider, newsline, highlights)
├── /join-as-jawan                   Static info page
├── /join-as-officer                 Static info page
│   ├── /join-as-officer/civilians
│   ├── /join-as-officer/ex-service
│   └── /join-as-officer/syllabus
├── /pages/{slug}                    Any dynamic page admin creates
├── /pdfs                            PDF library listing
│   └── /pdfs/{slug}
├── /study-material                  Study material listing
├── /books                           Books catalog
│   ├── /books/{slug}
│   ├── /cart
│   ├── /checkout
│   └── /order/{order_id}
├── /exams                           Exam list
│   └── /exams/{slug}/register
├── /subscribe/officer
├── /subscribe/soldier
├── /verify-otp
├── /unsubscribe/{token}
├── /reviews
├── /newsline
├── /about  /contact  /privacy  /terms
├── /sitemap.xml
└── /robots.txt
```

## Admin Panel

```
/admin/login
/admin                               Dashboard
/admin/orders   (pending / successful / rejected)
/admin/pages
/admin/registrations
/admin/officer-subscribers
/admin/soldier-subscribers
/admin/pdfs
/admin/study-material
/admin/books
/admin/exams
/admin/sliders
/admin/newsline
/admin/reviews
/admin/settings
/admin/change-password
/admin/logout
```

## Conventions
- URLs lowercase, words joined with hyphens
- Slugs auto-generated from Title, editable
- `menu_name` independent of page title
- Top nav shows only root pages (`parent_id = null`); children appear in dropdown
- Admin controls display order (`sort`)
- Legacy URLs 301-redirect via the `redirects` table
