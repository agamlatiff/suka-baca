# Sukabaca - Backend Development Context

## Tech Stack

- **Framework**: Laravel 11
- **Database**: MySQL
- **Authentication**: Laravel Breeze (Sanctum-based)
- **Frontend**: Blade + Tailwind CSS + Alpine.js

---

## Database Migrations

Migrations are located in `database/migrations/` and must be run in order:

| Order | Migration File                                   | Table         | Dependencies           |
| ----- | ------------------------------------------------ | ------------- | ---------------------- |
| 1     | `2024_01_01_000001_create_users_table.php`       | `users`       | None                   |
| 2     | `2024_01_01_000002_create_categories_table.php`  | `categories`  | None                   |
| 3     | `2024_01_01_000003_create_books_table.php`       | `books`       | `categories`           |
| 4     | `2024_01_01_000004_create_book_copies_table.php` | `book_copies` | `books`                |
| 5     | `2024_01_01_000005_create_borrowings_table.php`  | `borrowings`  | `users`, `book_copies` |

### Run Migrations

```bash
php artisan migrate
```

### Rollback All

```bash
php artisan migrate:rollback
```

---

## Tables Overview

### 1. `users`

User authentication and profile data.

| Column                     | Type                 | Notes              |
| -------------------------- | -------------------- | ------------------ |
| `id`                       | BIGINT UNSIGNED      | PK, auto-increment |
| `name`                     | VARCHAR(255)         | Full name          |
| `email`                    | VARCHAR(255)         | Unique, for login  |
| `password`                 | VARCHAR(255)         | Hashed             |
| `role`                     | ENUM('admin','user') | Default: 'user'    |
| `phone`                    | VARCHAR(20)          | Nullable           |
| `email_verified_at`        | TIMESTAMP            | Nullable           |
| `remember_token`           | VARCHAR(100)         | Laravel session    |
| `created_at`, `updated_at` | TIMESTAMP            | Laravel timestamps |

**Indexes**: `idx_users_role`

---

### 2. `categories`

Book genre/category master data.

| Column                     | Type            | Notes    |
| -------------------------- | --------------- | -------- |
| `id`                       | BIGINT UNSIGNED | PK       |
| `name`                     | VARCHAR(100)    | Unique   |
| `description`              | TEXT            | Nullable |
| `created_at`, `updated_at` | TIMESTAMP       |          |

---

### 3. `books`

Book master data (title info).

| Column                     | Type            | Notes            |
| -------------------------- | --------------- | ---------------- |
| `id`                       | BIGINT UNSIGNED | PK               |
| `title`                    | VARCHAR(255)    | Searchable       |
| `author`                   | VARCHAR(255)    |                  |
| `category_id`              | BIGINT UNSIGNED | FK → categories  |
| `isbn`                     | VARCHAR(20)     | Unique, nullable |
| `description`              | TEXT            | Nullable         |
| `total_copies`             | UNSIGNED INT    | Default: 0       |
| `available_copies`         | UNSIGNED INT    | Default: 0       |
| `times_borrowed`           | UNSIGNED INT    | Counter          |
| `created_at`, `updated_at` | TIMESTAMP       |                  |

**FK**: `category_id` → `categories(id)` ON DELETE RESTRICT  
**Indexes**: `idx_books_title`, `idx_books_available_copies`

---

### 4. `book_copies`

Physical book copies/instances.

| Column                     | Type            | Notes                                          |
| -------------------------- | --------------- | ---------------------------------------------- |
| `id`                       | BIGINT UNSIGNED | PK                                             |
| `book_id`                  | BIGINT UNSIGNED | FK → books                                     |
| `copy_code`                | VARCHAR(50)     | Unique (e.g., BK001-C01)                       |
| `status`                   | ENUM            | 'available', 'borrowed', 'maintenance', 'lost' |
| `notes`                    | TEXT            | Nullable (condition notes)                     |
| `created_at`, `updated_at` | TIMESTAMP       |                                                |

**FK**: `book_id` → `books(id)` ON DELETE CASCADE  
**Indexes**: `idx_book_copies_status`, `idx_book_copies_book_status` (composite)

---

### 5. `borrowings`

Borrowing transactions.

| Column                     | Type            | Notes                           |
| -------------------------- | --------------- | ------------------------------- |
| `id`                       | BIGINT UNSIGNED | PK                              |
| `borrowing_code`           | VARCHAR(50)     | Unique (e.g., BRW-20241221-001) |
| `user_id`                  | BIGINT UNSIGNED | FK → users                      |
| `book_copy_id`             | BIGINT UNSIGNED | FK → book_copies                |
| `borrowed_at`              | DATE            | Borrow date                     |
| `due_date`                 | DATE            | Return due date                 |
| `returned_at`              | DATE            | Nullable (null = not returned)  |
| `rental_fee`               | DECIMAL(10,2)   | Default: 0.00                   |
| `late_fee`                 | DECIMAL(10,2)   | Default: 0.00                   |
| `total_fee`                | DECIMAL(10,2)   | rental + late                   |
| `is_paid`                  | BOOLEAN         | Default: false                  |
| `status`                   | ENUM            | 'active', 'returned', 'overdue' |
| `days_late`                | INT             | Default: 0                      |
| `created_at`, `updated_at` | TIMESTAMP       |                                 |

**FKs**:

- `user_id` → `users(id)` ON DELETE RESTRICT
- `book_copy_id` → `book_copies(id)` ON DELETE RESTRICT

**Indexes**:

- `idx_borrowings_status`
- `idx_borrowings_due_date`
- `idx_borrowings_is_paid`
- `idx_borrowings_user_status` (composite)
- `idx_borrowings_status_duedate` (composite)

---

## Foreign Key Behavior

| Relationship                              | On Delete | Reason                            |
| ----------------------------------------- | --------- | --------------------------------- |
| `books.category_id` → `categories`        | RESTRICT  | Cannot delete category with books |
| `book_copies.book_id` → `books`           | CASCADE   | Delete copies when book deleted   |
| `borrowings.user_id` → `users`            | RESTRICT  | Preserve borrowing history        |
| `borrowings.book_copy_id` → `book_copies` | RESTRICT  | Preserve borrowing history        |

---

## Models to Create

Create Eloquent models in `app/Models/`:

```
app/Models/
├── User.php        (exists, modify for role)
├── Category.php
├── Book.php
├── BookCopy.php
└── Borrowing.php
```

### Relationships

```php
// User.php
public function borrowings(): HasMany

// Category.php
public function books(): HasMany

// Book.php
public function category(): BelongsTo
public function copies(): HasMany

// BookCopy.php
public function book(): BelongsTo
public function borrowings(): HasMany
public function activeBorrowing(): HasOne

// Borrowing.php
public function user(): BelongsTo
public function bookCopy(): BelongsTo
```

---

## Controllers to Create

```
app/Http/Controllers/
├── Admin/
│   ├── DashboardController.php
│   ├── BookController.php
│   ├── CategoryController.php
│   ├── BookCopyController.php
│   ├── BorrowingController.php
│   └── UserController.php
└── User/
    ├── DashboardController.php
    ├── CatalogController.php
    └── BorrowingController.php
```

---

## Routes Structure

```php
// routes/web.php

// Admin routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index']);
    Route::resource('books', Admin\BookController::class);
    Route::resource('categories', Admin\CategoryController::class);
    Route::resource('book-copies', Admin\BookCopyController::class);
    Route::resource('borrowings', Admin\BorrowingController::class);
    Route::resource('users', Admin\UserController::class);
});

// User routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [User\DashboardController::class, 'index']);
    Route::get('/catalog', [User\CatalogController::class, 'index']);
    Route::get('/catalog/{book}', [User\CatalogController::class, 'show']);
    Route::post('/borrow/{book}', [User\BorrowingController::class, 'store']);
    Route::get('/my-borrowings', [User\BorrowingController::class, 'index']);
});
```

---

## Middleware

Create custom middleware for role checking:

```php
// app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, string $role)
{
    if ($request->user()->role !== $role) {
        abort(403);
    }
    return $next($request);
}
```

Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

---

## Next Steps

1. [ ] Create Eloquent models with relationships
2. [ ] Create seeders for initial data (admin user, categories)
3. [ ] Create Form Requests for validation
4. [ ] Create Controllers
5. [ ] Create Blade views
6. [ ] Implement business logic (borrowing, returning, fee calculation)

---

## Common Queries Reference

### Available books

```php
Book::where('available_copies', '>', 0)->get();
```

### User's active borrowings

```php
Borrowing::where('user_id', $userId)
    ->whereIn('status', ['active', 'overdue'])
    ->with('bookCopy.book')
    ->get();
```

### Overdue borrowings

```php
Borrowing::where('status', 'active')
    ->where('due_date', '<', now())
    ->get();
```

### Available copies for a book

```php
BookCopy::where('book_id', $bookId)
    ->where('status', 'available')
    ->get();
```
