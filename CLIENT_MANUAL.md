# Territorial Army Website — Admin Manual

**For the admin user. No technical knowledge needed.**

---

## How to log in

1. Open your browser (Chrome, Edge, Firefox — any)
2. Go to: **https://jointerritorialarmy.com/admin**
3. Enter your email and password
4. Click **Sign in**

If you forget your password, click "Forgot password?" on the login page.

---

## What you'll see

A **purple header** at the top, a **sidebar menu** on the left with these sections, and the main content area.

### Sidebar menu groups

- **Commerce** — Book orders from customers
- **Content** — Website pages, homepage slider, news, reviews
- **Library** — Books for sale, PDFs, study material, exam list
- **Subscribers** — People who signed up, exam registrations

Click any item to open it.

---

## Common tasks

### ➕ Add a new page to the website

1. Sidebar → **Pages** → click **Add page** (top right)
2. Fill in:
   - **Title** — What appears at the top of the page (e.g. "Syllabus 2027")
   - **Menu name** — Short label shown in the website's top navigation (e.g. "Syllabus")
   - **Parent page** — Choose one if this should be a sub-page under another (optional)
   - **Status** — Choose **Publish** to make it live, or **Draft** to save without showing
   - **Content** — Write your text. Use the toolbar for bold, lists, links, images, headings.
3. Click **Create**

The page is immediately live on the website.

---

### 📄 Upload a PDF

1. Sidebar → **PDFs** → click **New Pdf**
2. Fill in:
   - **Title** — e.g. "TAC Exam Previous Paper 2025"
   - **Category** — e.g. "Previous Papers" (any label you like)
   - **Description** — optional, 1-2 sentences
   - **File path** — click the box and choose your PDF file
3. Click **Create**

The PDF now appears on `yoursite.com/pdfs` with a Download button.

---

### 📚 Add a book for sale

1. Sidebar → **Books** → **New Book**
2. Fill in:
   - **Title**, **Author**
   - **Price** — regular price in rupees
   - **Discount price** — leave blank if no discount
   - **Stock** — number of copies available
   - **Description**
   - **Cover image** — upload a square or portrait image
3. Keep **Is active** toggled on
4. Click **Create**

The book appears on `yoursite.com/books` with a Buy button.

---

### 🛒 Manage orders

1. Sidebar → **Orders**
2. You'll see four tabs at the top: **All**, **Pending**, **Successful**, **Rejected**
3. Click any order to see customer details
4. Change the status using the dropdown, add a consignment (tracking) number
5. Click **Save**

#### Download all orders as Excel

- Top-right of the Orders list → click **Download Excel**
- Choose a status filter (or leave blank for all)
- Click **Download** → file saves to your computer

---

### 📊 Export subscriber list (for WhatsApp / email campaigns)

1. Sidebar → **Officer Subscribers** (or **Soldier Subscribers**)
2. Top right → three green buttons:
   - **Download subscribed** — verified subscribers only
   - **Download unverified** — people who signed up but didn't confirm
   - **Download unsubscribed** — people who opted out
3. Click the one you want → Excel file downloads

---

### 🖼️ Change the homepage slider

1. Sidebar → **Sliders**
2. **New** to add an image, or click an existing one to edit
3. Upload image, add a title + caption + optional "Learn more" link
4. **Drag rows** in the list to reorder — top shows first
5. Toggle **Is active** off to hide one without deleting it

---

### 📢 Add a breaking news announcement

1. Sidebar → **Newsline** → **New**
2. Type the headline (e.g. "TAC 2027 exam dates announced")
3. Optional link URL
4. Optional start/end dates (otherwise shows immediately, forever)
5. **Create**

Appears in the scrolling ticker on the homepage.

---

### ⭐ Approve a review

1. Sidebar → **Reviews**
2. Find the row → click the green **Publish** button (only shows for unpublished reviews)
3. Done — review is now live on the Reviews page

To add your own review: **New**, fill name + rating + comment, toggle **Is published** on, **Create**.

---

### 🎓 Add an exam to the Exam list

1. Sidebar → **Exam Names** → **New**
2. Name (e.g. "TAC Exam 2027"), exam date, description
3. **Create**

Appears on `/exams` with a "Register for results" button that the public fills in.

---

### 📋 View exam result registrations

1. Sidebar → **Result Registrations**
2. See all people who registered
3. Filter by **exam** or **zone** using the filters at the top
4. **Download Excel** or **Download Excel by zone** at the top right

---

### 🔐 Change your password

1. Top-right corner of the admin → click your profile (circle with your initials)
2. **Profile** → change password section → enter new password → **Save**

---

### ➕ Add another admin user

You can't do this from the admin panel directly (by design, for security). Ask the developer to add admins by email.

---

## What you should NOT touch

- **Settings** section — these are site-wide (title, contact info). Change the contact info if needed, but leave the icon settings alone unless the developer tells you to.

---

## When something looks wrong

1. **Hard refresh the page**: Ctrl + Shift + R (or Cmd + Shift + R on Mac)
2. If still broken, **log out and log back in**
3. If still broken, **take a screenshot** and send it to the developer

---

## Quick cheat sheet

| I want to... | Go to... |
|---|---|
| Add a page | Pages → Add page |
| Upload a PDF | PDFs → New Pdf |
| List a new book for sale | Books → New Book |
| Check today's orders | Orders → Pending tab |
| Ship an order | Orders → click it → Change status to Successful |
| Download subscriber emails | Officer/Soldier Subscribers → Download subscribed |
| Add a homepage slider image | Sliders → New |
| Post a news headline | Newsline → New |
| Approve a review | Reviews → green Publish button |
| Add an exam | Exam Names → New |
| Download exam registrations | Result Registrations → Download Excel |

---

## That's it

The admin is designed so you can learn it in **30 minutes** and never need the developer for day-to-day tasks.

For anything not in this manual, contact the developer.
