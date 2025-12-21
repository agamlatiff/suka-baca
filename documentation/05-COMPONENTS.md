# Feature Modules

## Module Overview

| Module          | Complexity | Admin | User | Key Functionality           |
| --------------- | ---------- | ----- | ---- | --------------------------- |
| Authentication  | Low        | ✅    | ✅   | Login, Register, Session    |
| Book Management | Medium     | ✅    | ❌   | CRUD books, Copy tracking   |
| Catalog         | Low        | ❌    | ✅   | Browse, Search, Filter      |
| Borrowing       | Medium     | ✅    | ✅   | Borrow, Return, Tracking    |
| Fees & Fines    | Low        | ✅    | ✅   | Rental fees, Late penalties |
| Dashboard       | Low        | ✅    | ✅   | Statistics, Overview        |

---

## Module A: Authentication

### Features

-   Login (Admin/User)
-   Logout
-   User registration
-   Session management

### Sub-features

| Feature           | Description                             |
| ----------------- | --------------------------------------- |
| Register          | Name, email, password, phone (optional) |
| Profile           | View/edit basic profile                 |
| Role-based access | Admin vs User permissions               |

---

## Module B: Book Management (Admin)

### Features

-   CRUD operations for books
-   Book copy tracking
-   Availability status

### Sub-features

| Feature           | Description                                           |
| ----------------- | ----------------------------------------------------- |
| Add Book          | Title, Author, Category (dropdown), Number of copies  |
| Auto-generate IDs | Each copy gets unique ID (e.g., BK001-C01, BK001-C02) |
| Copy Status View  | ✅ Available, 📖 Borrowed (by whom, since when)       |
| Edit/Delete       | Modify or remove books                                |
| Search            | By title or author                                    |

### Book Form Fields

```
- Title (required)
- Author (required)
- Category (dropdown, required)
- ISBN (optional)
- Description (optional)
- Rental Fee (required, dynamic per book)
- Number of copies (required, default: 1)
```

---

## Module C: Catalog (User)

### Features

-   Browse available books
-   View book details
-   Borrow action

### Sub-features

| Feature         | Description                                     |
| --------------- | ----------------------------------------------- |
| Book List       | Title, Author, Category, Available/Total copies |
| Availability    | "Available" or "Out of Stock" badge             |
| Category Filter | Filter by category                              |
| Search          | By title or author                              |
| Book Detail     | Full info, borrow count, "Borrow" button        |

---

## Module D: Borrowing

### Features

-   User borrows book
-   Admin views all borrowings
-   Return process

### Sub-features

#### User Actions

| Action           | Description                      |
| ---------------- | -------------------------------- |
| Borrow           | Click "Borrow" on available book |
| Choose duration  | 7 or 14 days                     |
| Get confirmation | Borrowing code + due date        |
| View active      | List of current borrowings       |
| View history     | Past borrowings                  |

#### Admin Actions

| Action         | Description                           |
| -------------- | ------------------------------------- |
| View all       | Table: Who, Book, Copy, Dates, Status |
| Filter         | Active / Returned / Overdue           |
| Process return | Click "Return" button                 |
| Auto-update    | Copy → Available, Counter +1          |

---

## Module E: Fees & Fines

### Features

-   Dynamic rental fee per book (set by admin)
-   Admin-configurable late fee per day (system setting)
-   Automatic late fee calculation

### Configuration

| Setting          | Type    | Location        | Description                        |
| ---------------- | ------- | --------------- | ---------------------------------- |
| Rental fee       | Dynamic | Book form       | Each book has its own rental price |
| Late fee per day | Global  | System Settings | Admin-configurable penalty rate    |

### Fee Calculation

```
Rental Fee = book.rental_fee (dynamic per book)
Late Fee = Days Late × settings.late_fee_per_day
Total Fee = Rental Fee + Late Fee
```

### Payment Process

1. User pays via cash/bank transfer
2. Admin marks borrowing as "Paid"
3. No payment gateway integration (manual)

---

## Module F: Dashboard

### Admin Dashboard

| Widget             | Description                    |
| ------------------ | ------------------------------ |
| Total Books        | Number of unique titles        |
| Total Copies       | All physical copies            |
| Available          | Copies available for borrowing |
| Currently Borrowed | Active borrowings              |
| Total Users        | Registered members             |
| Recent Borrowings  | Last 5-10 transactions         |
| Top Books          | Top 5 most borrowed            |

### User Dashboard

| Widget            | Description              |
| ----------------- | ------------------------ |
| Active Borrowings | Currently borrowed books |
| Due Soon          | Books due within 3 days  |
| Overdue           | Late returns             |
| Total Fees        | Current outstanding fees |
| Borrowing History | Past transactions        |
