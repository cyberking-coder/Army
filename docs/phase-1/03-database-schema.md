# Phase 1 — Database Schema

MySQL 8, utf8mb4_unicode_ci, InnoDB. All tables have `id`, `created_at`, `updated_at`.

## Core

### `admins`
`id`, `name`, `email (unique)`, `password`, `role (super_admin|editor)`, `last_login_at`

### `pages` (self-referencing tree)
`id`, `title`, `slug (unique)`, `parent_id → pages`, `menu_name`, `description (html)`, `meta_title`, `meta_description`, `sort`, `status (draft|publish)`

### `sliders`
`id`, `image_path`, `title`, `caption`, `link_url`, `sort`, `is_active`

### `newsline`
`id`, `headline`, `link_url`, `starts_at`, `ends_at`, `is_active`, `sort`

### `reviews`
`id`, `name`, `rating(1-5)`, `comment`, `is_published`, `submitted_at`

## File libraries

### `pdfs`
`id`, `title`, `slug`, `description`, `file_path (R2)`, `file_size_kb`, `category`, `downloads_count`, `is_active`

### `study_material`
`id`, `title`, `slug`, `description`, `file_path`, `subject`, `is_active`

### `books`
`id`, `title`, `slug`, `author`, `description`, `cover_image_path`, `price`, `discount_price`, `stock`, `is_active`

### `exams`
`id`, `name`, `slug`, `description`, `exam_date`, `is_active`

## Subscribers

### `officer_subscribers`
`id`, `full_name`, `mobile`, `email`, `status (subscribed|unverified|unsubscribed)`, `otp`, `otp_expires_at`, `verified_at`, `unsubscribed_at`, `ip_address`

### `soldier_subscribers`
`id`, `first_name`, `last_name`, `mobile`, `email`, `status`, `otp`, `otp_expires_at`, `verified_at`, `unsubscribed_at`, `ip_address`

### `result_registrations`
`id`, `exam_id → exams`, `name`, `mobile`, `email`, `roll_no`, `zone`, `submitted_at`

## Orders

### `orders`
`id`, `order_number (unique)`, `full_name`, `mobile`, `email`, `shipping_address`, `city`, `state`, `pincode`, `subtotal`, `discount`, `final_cost`, `status (pending|successful|rejected)`, `payment_id`, `razorpay_order_id`, `consignment_number`, `notes`

### `order_items`
`id`, `order_id → orders`, `book_id → books`, `quantity`, `unit_price`, `line_total`

## Supporting
- `settings` — key/value site config
- `redirects` — legacy URL → new URL
- `activity_log` — admin audit trail
- `jobs`, `cache`, `sessions`, `password_reset_tokens` — Laravel internals

## Index Strategy
- `pages.slug` unique, `pages.parent_id` index
- `orders.status`, `orders.order_number` unique
- `*_subscribers.status`, `.mobile`, `.email`
- `result_registrations.exam_id, .zone`
- `activity_log.created_at`

## Year-1 Storage Estimate
DB ~500 MB · R2 PDFs ~5 GB
