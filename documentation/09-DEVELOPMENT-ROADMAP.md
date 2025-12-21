# Development Roadmap

## Project Timeline

**Total Estimated Duration:** 13 working days (~2.5 weeks)

## Language Convention

| Area            | Language             | Example                                 |
| --------------- | -------------------- | --------------------------------------- |
| **Code**        | English              | `$book->title`, `function getLateFee()` |
| **Database**    | English              | `books`, `category_id`, `borrowed_at`   |
| **UI/Labels**   | **Bahasa Indonesia** | "Judul Buku", "Tanggal Pinjam", "Denda" |
| **Messages**    | **Bahasa Indonesia** | "Buku berhasil dipinjam!"               |
| **Admin Panel** | **Bahasa Indonesia** | Menu, form labels, notifications        |

> **Target Market:** Indonesia  
> **Semua teks yang tampil ke pengguna harus dalam Bahasa Indonesia.**

---

## Progress Overview

| Phase              | Status      | Days      |
| ------------------ | ----------- | --------- |
| Setup              | ✅ Complete | Day 1     |
| Books & Categories | 🔲 Pending  | Day 2-3   |
| Book Copies        | 🔲 Pending  | Day 4-5   |
| User Catalog       | 🔲 Pending  | Day 6     |
| Borrowing System   | 🔲 Pending  | Day 7-9   |
| Fees & Fines       | 🔲 Pending  | Day 10    |
| Admin Dashboard    | 🔲 Pending  | Day 11    |
| User Dashboard     | 🔲 Pending  | Day 12    |
| Testing & Polish   | 🔲 Pending  | Day 13-14 |

---

## Day 1: Project Setup ✅

-   [x] Install Laravel 11
-   [x] Configure MySQL database
-   [x] Install Laravel Breeze
-   [x] Install Laravel Filament v4
-   [x] Set up Tailwind CSS
-   [x] Create initial migrations (users, categories, books, book_copies, borrowings)
-   [x] Create Setting model and migration
-   [x] Add rental_fee migration to books
-   [x] Create all Eloquent models (User, Book, Category, BookCopy, Borrowing, Setting)

**Files Created:**

-   `database/migrations/` - 7 migration files
-   `app/Models/` - 6 model files
-   `app/Providers/Filament/AdminPanelProvider.php`

---

## Day 2-3: Books & Categories 🔲

### Category Management

-   [ ] Create CategorySeeder with sample data
-   [ ] Create CategoryResource (Filament)
    -   [ ] List: name, description, books count
    -   [ ] Form: name (required), description (optional)
    -   [ ] Actions: create, edit, delete

### Book Management

-   [ ] Create BookResource (Filament)
    -   [ ] List: title, author, category, rental_fee, available/total copies
    -   [ ] Form: title, author, category (dropdown), isbn, description, rental_fee, copies_count
    -   [ ] Search: by title, author
    -   [ ] Filter: by category, availability
    -   [ ] Sort: by title, author, times_borrowed

**Files to Create:**

-   `app/Filament/Resources/CategoryResource.php`
-   `app/Filament/Resources/BookResource.php`
-   `database/seeders/CategorySeeder.php`

---

## Day 4-5: Book Copies 🔲

### Copy Management

-   [ ] Implement auto-generate copy codes (format: BK001-C01, BK001-C02)
-   [ ] Create CopiesRelationManager for BookResource
    -   [ ] List: copy_code, status, notes
    -   [ ] Actions: create, edit, update status
-   [ ] Auto-create copies when book is created
-   [ ] Update `available_copies` on Book when copy status changes
-   [ ] Create BookCopyResource (optional, for standalone view)

### Copy Status Logic

-   [ ] Available → Borrowed (when borrowed)
-   [ ] Borrowed → Available (when returned)
-   [ ] Maintenance status (manual)
-   [ ] Lost status (manual)

**Files to Create:**

-   `app/Filament/Resources/BookResource/RelationManagers/CopiesRelationManager.php`
-   `app/Observers/BookCopyObserver.php` (for status sync)

---

## Day 6: User Catalog 🔲

### Catalog Controller

-   [ ] Create CatalogController
    -   [ ] index() - list books with filters
    -   [ ] show() - book detail

### Catalog Views

-   [ ] Create `resources/views/catalog/index.blade.php`
    -   [ ] Book grid/list view
    -   [ ] Search input (title/author)
    -   [ ] Category dropdown filter
    -   [ ] Availability filter toggle
    -   [ ] Pagination
-   [ ] Create `resources/views/catalog/show.blade.php`
    -   [ ] Book info (title, author, category, description)
    -   [ ] Rental fee display
    -   [ ] Availability badge
    -   [ ] "Borrow" button (if available)

### Routes

-   [ ] GET `/catalog` → catalog.index
-   [ ] GET `/catalog/{book}` → catalog.show

**Files to Create:**

-   `app/Http/Controllers/CatalogController.php`
-   `resources/views/catalog/index.blade.php`
-   `resources/views/catalog/show.blade.php`

---

## Day 7-9: Borrowing System 🔲

### Borrowing Controller

-   [ ] Create BorrowingController
    -   [ ] store() - create new borrowing
    -   [ ] index() - user's borrowings
-   [ ] Create BorrowBookRequest (form validation)

### Borrow Flow (User)

-   [ ] User clicks "Borrow" on book detail
-   [ ] Select duration (7 or 14 days modal/form)
-   [ ] System auto-assigns available copy
-   [ ] Generate borrowing code (BRW-YYYYMMDD-XXX)
-   [ ] Calculate due date
-   [ ] Set rental_fee from book.rental_fee
-   [ ] Update copy status to 'borrowed'
-   [ ] Decrement book.available_copies
-   [ ] Redirect to my-borrowings with success message

### Borrowing Resource (Filament Admin)

-   [ ] Create BorrowingResource
    -   [ ] List: code, user, book, copy, dates, status, fees, is_paid
    -   [ ] Filters: status (active/returned/overdue), is_paid
    -   [ ] Actions: Return, Mark as Paid

### Return Flow (Admin)

-   [ ] Admin clicks "Return" action
-   [ ] Calculate late_fee if overdue
-   [ ] Update total_fee
-   [ ] Set returned_at = today
-   [ ] Set status = 'returned'
-   [ ] Update copy status to 'available'
-   [ ] Increment book.available_copies
-   [ ] Increment book.times_borrowed

### User Borrowings View

-   [ ] Create `resources/views/borrowings/index.blade.php`
    -   [ ] Active borrowings list
    -   [ ] Status badge (Active/Returned/Overdue)
    -   [ ] Days remaining display
    -   [ ] Fee information
    -   [ ] History tab/section

**Files to Create:**

-   `app/Http/Controllers/BorrowingController.php`
-   `app/Http/Requests/BorrowBookRequest.php`
-   `app/Filament/Resources/BorrowingResource.php`
-   `resources/views/borrowings/index.blade.php`

---

## Day 10: Fees & Fines 🔲

### Settings Resource (Filament)

-   [ ] Create SettingResource
    -   [ ] List: key, value, description
    -   [ ] Form: key (readonly), value (editable)
    -   [ ] Seed default values if not exist

### Fee Calculation

-   [ ] Implement LateFeeCalculator service/helper
-   [ ] Auto-calculate on return:
    -   `days_late = max(0, returned_at - due_date)`
    -   `late_fee = days_late × Setting::getLateFeePerDay()`
    -   `total_fee = rental_fee + late_fee`

### Payment Marking

-   [ ] "Mark as Paid" action in BorrowingResource
-   [ ] Show unpaid count badge on dashboard
-   [ ] Filter by payment status

### Fee Display

-   [ ] Show fees on user's borrowing list
-   [ ] Show total outstanding fees on user dashboard

**Files to Create:**

-   `app/Filament/Resources/SettingResource.php`
-   `database/seeders/SettingSeeder.php`

---

## Day 11: Admin Dashboard 🔲

### Dashboard Widgets (Filament)

-   [ ] Create StatsOverview widget
    -   [ ] Total Books (titles)
    -   [ ] Total Copies
    -   [ ] Available Copies
    -   [ ] Borrowed Copies
    -   [ ] Total Users
    -   [ ] Active Borrowings
-   [ ] Create RecentBorrowingsTable widget
    -   [ ] Last 10 borrowings
    -   [ ] Quick actions
-   [ ] Create TopBooksChart widget
    -   [ ] Top 5 by times_borrowed
-   [ ] Configure dashboard layout

**Files to Create:**

-   `app/Filament/Widgets/StatsOverview.php`
-   `app/Filament/Widgets/RecentBorrowingsTable.php`
-   `app/Filament/Widgets/TopBooksChart.php`

---

## Day 12: User Dashboard 🔲

### Dashboard Controller

-   [ ] Create DashboardController
    -   [ ] index() - user dashboard data

### Dashboard View

-   [ ] Create `resources/views/dashboard.blade.php`
    -   [ ] Active borrowings card
    -   [ ] Due soon alert (< 3 days)
    -   [ ] Overdue warning
    -   [ ] Outstanding fees total
    -   [ ] Recent borrowing history

**Files to Create/Update:**

-   `app/Http/Controllers/DashboardController.php`
-   `resources/views/dashboard.blade.php`

---

## Day 13-14: Testing & Polish 🔲

### Functional Testing

-   [ ] Test user registration
-   [ ] Test user login/logout
-   [ ] Test catalog browsing
-   [ ] Test search and filters
-   [ ] Test borrowing flow
-   [ ] Test return process
-   [ ] Test fee calculation
-   [ ] Test payment marking
-   [ ] Test admin dashboard
-   [ ] Test user dashboard

### Bug Fixes

-   [ ] Fix any issues found during testing
-   [ ] Edge cases (no copies, overdue, etc.)

### UI/UX Polish

-   [ ] Consistent styling across pages
-   [ ] Loading states
-   [ ] Error messages
-   [ ] Success notifications
-   [ ] Mobile responsive check

### Final Checks

-   [ ] All routes working
-   [ ] All Filament resources complete
-   [ ] Database seeding works
-   [ ] Documentation up to date

---

## Milestones

| Milestone | Target | Deliverable                         | Status |
| --------- | ------ | ----------------------------------- | ------ |
| M1        | Day 1  | Project setup complete              | ✅     |
| M2        | Day 3  | Admin can manage books & categories | 🔲     |
| M3        | Day 6  | Users can browse catalog            | 🔲     |
| M4        | Day 9  | Full borrowing flow working         | 🔲     |
| M5        | Day 12 | All dashboards complete             | 🔲     |
| M6        | Day 14 | MVP ready for deployment            | 🔲     |

---

## Quick Reference: Files to Create

### Controllers

-   `app/Http/Controllers/CatalogController.php`
-   `app/Http/Controllers/BorrowingController.php`
-   `app/Http/Controllers/DashboardController.php`

### Form Requests

-   `app/Http/Requests/BorrowBookRequest.php`

### Filament Resources

-   `app/Filament/Resources/CategoryResource.php`
-   `app/Filament/Resources/BookResource.php`
-   `app/Filament/Resources/BorrowingResource.php`
-   `app/Filament/Resources/UserResource.php`
-   `app/Filament/Resources/SettingResource.php`

### Filament Widgets

-   `app/Filament/Widgets/StatsOverview.php`
-   `app/Filament/Widgets/RecentBorrowingsTable.php`
-   `app/Filament/Widgets/TopBooksChart.php`

### Views

-   `resources/views/catalog/index.blade.php`
-   `resources/views/catalog/show.blade.php`
-   `resources/views/borrowings/index.blade.php`
-   `resources/views/dashboard.blade.php`

### Seeders

-   `database/seeders/CategorySeeder.php`
-   `database/seeders/SettingSeeder.php`
-   `database/seeders/AdminSeeder.php`
