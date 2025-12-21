# User Flows

## Flow 1: User Borrows a Book

```mermaid
flowchart TD
    A[User Login] --> B[Browse Catalog]
    B --> C[Click Book]
    C --> D{Book Available?}
    D -->|No| E[Show Out of Stock]
    D -->|Yes| F[Click Borrow Button]
    F --> G[Select Duration]
    G --> H[Confirm Borrow]
    H --> I[System Assigns Copy]
    I --> J[Show Borrowing Code + Due Date]
    J --> K[User Pays Admin]
    K --> L[Admin Marks Paid]
```

### Steps Detail

| Step | Action                    | System Response                      |
| ---- | ------------------------- | ------------------------------------ |
| 1    | User logs in              | Redirect to dashboard                |
| 2    | User browses catalog      | Display book list with availability  |
| 3    | User clicks on a book     | Show book detail page                |
| 4    | User clicks "Borrow"      | Show duration selection modal        |
| 5    | User selects 7 or 14 days | Calculate due date                   |
| 6    | User confirms             | System auto-assigns available copy   |
| 7    | Success                   | Display borrowing code + return date |
| 8    | User pays (cash/transfer) | Outside system                       |
| 9    | Admin marks as paid       | Borrowing status updated             |

---

## Flow 2: Admin Processes Book Return

```mermaid
flowchart TD
    A[Admin Login] --> B[Go to Borrowings Page]
    B --> C[Filter by Active/Overdue]
    C --> D[Find Borrowing]
    D --> E[Click Return Button]
    E --> F{Is Overdue?}
    F -->|Yes| G[Calculate Late Fee]
    F -->|No| H[No Late Fee]
    G --> I[Update Total Fee]
    H --> I
    I --> J[Set Copy Status = Available]
    J --> K[Increment times_borrowed]
    K --> L[Status = Returned]
```

### Steps Detail

| Step | Action                     | System Response                    |
| ---- | -------------------------- | ---------------------------------- |
| 1    | Admin opens Borrowings     | Display all borrowings table       |
| 2    | Admin filters "Active"     | Show only active borrowings        |
| 3    | Admin finds the borrowing  | Search by user name or book        |
| 4    | Admin clicks "Return"      | Prompt confirmation                |
| 5    | System calculates late fee | If overdue, add penalty            |
| 6    | Copy marked available      | `book_copies.status = 'available'` |
| 7    | Counter incremented        | `books.times_borrowed += 1`        |
| 8    | Borrowing closed           | `borrowings.status = 'returned'`   |

---

## Flow 3: User Registration

```mermaid
flowchart TD
    A[Visit Register Page] --> B[Fill Form]
    B --> C[Submit]
    C --> D{Validation OK?}
    D -->|No| E[Show Errors]
    E --> B
    D -->|Yes| F[Create User Account]
    F --> G[Auto Login]
    G --> H[Redirect to Dashboard]
```

### Form Fields

| Field            | Required | Validation             |
| ---------------- | -------- | ---------------------- |
| Name             | ✅       | Max 255 chars          |
| Email            | ✅       | Valid email, unique    |
| Password         | ✅       | Min 8 chars            |
| Confirm Password | ✅       | Must match             |
| Phone            | ❌       | Optional, max 20 chars |

---

## Flow 4: Admin Adds New Book

```mermaid
flowchart TD
    A[Admin Login] --> B[Go to Books Management]
    B --> C[Click Add Book]
    C --> D[Fill Book Form]
    D --> E[Set Number of Copies]
    E --> F[Submit]
    F --> G[Create Book Record]
    G --> H[Auto-Generate Copy IDs]
    H --> I[Redirect to Book List]
```

### Copy Code Generation

```
Format: {BOOK_CODE}-C{NUMBER}
Example: If book code is "BK001" with 3 copies:
- BK001-C01
- BK001-C02
- BK001-C03
```

---

## Flow 5: User Views Borrowing History

```mermaid
flowchart TD
    A[User Login] --> B[Click My Borrowings]
    B --> C[View Active Borrowings]
    C --> D[See Status Badges]
    D --> E{Filter Tab}
    E -->|Active| F[Show Current Loans]
    E -->|History| G[Show Past Loans]
```

### Status Badges

| Status   | Color     | Description        |
| -------- | --------- | ------------------ |
| Active   | 🟢 Green  | Currently borrowed |
| Overdue  | 🔴 Red    | Past due date      |
| Returned | 🔵 Blue   | Already returned   |
| Unpaid   | 🟡 Yellow | Payment pending    |

---

## Business Rules Summary

| Rule                | Description                             |
| ------------------- | --------------------------------------- |
| One copy per borrow | User borrows a specific copy            |
| Auto-assign         | System picks first available copy       |
| Duration options    | 7 days or 14 days                       |
| Late fee daily      | Calculated from day after due date      |
| Manual payment      | Admin confirms payment receipt          |
| No reservations     | Users cannot reserve out-of-stock books |
| Single category     | Each book has one category only         |
