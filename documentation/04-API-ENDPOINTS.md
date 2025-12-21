# Routes & Endpoints

## Authentication

All authenticated routes use **Laravel session-based authentication** (via Breeze).
Admin panel uses **Filament's built-in auth**.

---

## Route Overview

### Public Routes (Guest)

| Method | URI         | Name     | Description          |
| ------ | ----------- | -------- | -------------------- |
| GET    | `/`         | home     | Landing page         |
| GET    | `/login`    | login    | Login form           |
| POST   | `/login`    | -        | Process login        |
| GET    | `/register` | register | Registration form    |
| POST   | `/register` | -        | Process registration |
| POST   | `/logout`   | logout   | Logout user          |

### User Routes (Authenticated)

| Method | URI                      | Name             | Description    |
| ------ | ------------------------ | ---------------- | -------------- |
| GET    | `/dashboard`             | dashboard        | User dashboard |
| GET    | `/catalog`               | catalog.index    | Browse books   |
| GET    | `/catalog/{book}`        | catalog.show     | Book detail    |
| POST   | `/catalog/{book}/borrow` | catalog.borrow   | Borrow book    |
| GET    | `/my-borrowings`         | borrowings.index | My borrowings  |

### Admin Routes (Filament)

| Method | URI                 | Description       |
| ------ | ------------------- | ----------------- |
| GET    | `/admin`            | Admin dashboard   |
| GET    | `/admin/books`      | Manage books      |
| GET    | `/admin/categories` | Manage categories |
| GET    | `/admin/borrowings` | Manage borrowings |
| GET    | `/admin/users`      | Manage users      |
| GET    | `/admin/settings`   | System settings   |

---

## Detailed Route Specifications

### 1. GET `/catalog`

**Description:** Browse all available books with search, filter, and pagination.

**Query Parameters:**

| Parameter   | Type   | Default | Description                            |
| ----------- | ------ | ------- | -------------------------------------- |
| `search`    | string | null    | Search by title or author              |
| `category`  | int    | null    | Filter by category ID                  |
| `available` | bool   | null    | Show only available (1) or all (null)  |
| `sort`      | string | 'title' | Sort by: title, author, times_borrowed |
| `order`     | string | 'asc'   | Order: asc, desc                       |
| `page`      | int    | 1       | Page number                            |
| `per_page`  | int    | 12      | Items per page (max: 50)               |

**Example Request:**

```
GET /catalog?search=harry&category=1&available=1&sort=title&order=asc&page=1
```

**Controller Logic:**

```php
public function index(Request $request)
{
    $books = Book::query()
        ->when($request->search, fn($q, $search) =>
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%"))
        ->when($request->category, fn($q, $cat) =>
            $q->where('category_id', $cat))
        ->when($request->available, fn($q) =>
            $q->where('available_copies', '>', 0))
        ->orderBy($request->get('sort', 'title'), $request->get('order', 'asc'))
        ->paginate($request->get('per_page', 12));

    return view('catalog.index', compact('books'));
}
```

---

### 2. GET `/catalog/{book}`

**Description:** View book details.

**Route Parameter:**

| Parameter | Type | Description |
| --------- | ---- | ----------- |
| `book`    | int  | Book ID     |

**Response Data (to View):**

```php
[
    'book' => [
        'id' => 1,
        'title' => 'Harry Potter',
        'author' => 'J.K. Rowling',
        'category' => ['id' => 1, 'name' => 'Fiction'],
        'description' => '...',
        'rental_fee' => 5000.00,
        'total_copies' => 5,
        'available_copies' => 3,
        'times_borrowed' => 150,
    ]
]
```

---

### 3. POST `/catalog/{book}/borrow`

**Description:** Borrow a book.

**Route Parameter:**

| Parameter | Type | Description |
| --------- | ---- | ----------- |
| `book`    | int  | Book ID     |

**Request Body:**

| Field      | Type | Required | Validation |
| ---------- | ---- | -------- | ---------- |
| `duration` | int  | Yes      | in:7,14    |

**Validation Rules (Form Request):**

```php
// app/Http/Requests/BorrowBookRequest.php
public function rules(): array
{
    return [
        'duration' => ['required', 'integer', 'in:7,14'],
    ];
}

public function authorize(): bool
{
    $book = $this->route('book');
    return $book->available_copies > 0;
}
```

**Success Response:**

-   Redirect to `/my-borrowings` with flash message
-   Creates borrowing record
-   Decrements `available_copies`

**Error Response:**

-   Book not available: 403 with error message
-   Validation error: Back with errors

---

### 4. GET `/my-borrowings`

**Description:** List user's borrowings with filters.

**Query Parameters:**

| Parameter | Type   | Default | Description                       |
| --------- | ------ | ------- | --------------------------------- |
| `status`  | string | null    | Filter: active, returned, overdue |
| `page`    | int    | 1       | Page number                       |

**Response Data (to View):**

```php
[
    'borrowings' => [
        [
            'id' => 1,
            'borrowing_code' => 'BRW-20241221-001',
            'book_copy' => [
                'copy_code' => 'BK001-C01',
                'book' => ['title' => 'Harry Potter', ...]
            ],
            'borrowed_at' => '2024-12-21',
            'due_date' => '2024-12-28',
            'status' => 'active',
            'rental_fee' => 5000.00,
            'late_fee' => 0.00,
            'total_fee' => 5000.00,
            'is_paid' => false,
            'days_remaining' => 7,
        ]
    ]
]
```

---

## Form Request Validations

### RegisterRequest

```php
public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'phone' => ['nullable', 'string', 'max:20'],
    ];
}
```

### LoginRequest

```php
public function rules(): array
{
    return [
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ];
}
```

### BorrowBookRequest

```php
public function rules(): array
{
    return [
        'duration' => ['required', 'integer', 'in:7,14'],
    ];
}

public function messages(): array
{
    return [
        'duration.in' => 'Duration must be 7 or 14 days.',
    ];
}
```

---

## Soft Deletes

Soft delete enabled for tables that need history preservation:

| Table       | Soft Delete | Reason                          |
| ----------- | ----------- | ------------------------------- |
| users       | ❌ No       | Borrowings use restrictOnDelete |
| categories  | ❌ No       | Books use restrictOnDelete      |
| books       | ✅ Yes      | Keep borrowing history          |
| book_copies | ✅ Yes      | Keep borrowing history          |
| borrowings  | ❌ No       | Never delete, historical data   |
| settings    | ❌ No       | System config                   |

**Implementation:**

```php
// Add to Book and BookCopy models
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;
}
```

**Migration for soft deletes:**

```php
Schema::table('books', function (Blueprint $table) {
    $table->softDeletes();
});
```

---

## Error Handling

### Common Error Responses

| HTTP Code | Scenario           | User Experience            |
| --------- | ------------------ | -------------------------- |
| 401       | Not authenticated  | Redirect to login          |
| 403       | Not authorized     | Error page / flash message |
| 404       | Resource not found | 404 page                   |
| 422       | Validation error   | Back with errors           |
| 500       | Server error       | Error page                 |

### Flash Messages

```php
// Success
return redirect()->route('borrowings.index')
    ->with('success', 'Book borrowed successfully!');

// Error
return back()->with('error', 'Book is not available.');
```

---

## Route Definitions

### routes/web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowingController;

// Public
Route::get('/', fn() => view('welcome'))->name('home');

// Authenticated
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/catalog', [CatalogController::class, 'index'])
        ->name('catalog.index');
    Route::get('/catalog/{book}', [CatalogController::class, 'show'])
        ->name('catalog.show');
    Route::post('/catalog/{book}/borrow', [CatalogController::class, 'borrow'])
        ->name('catalog.borrow');

    Route::get('/my-borrowings', [BorrowingController::class, 'index'])
        ->name('borrowings.index');
});

require __DIR__.'/auth.php';
```

---

## Middleware

| Middleware | Purpose                   | Applied To            |
| ---------- | ------------------------- | --------------------- |
| `auth`     | Requires login            | All user routes       |
| `verified` | Email verified (optional) | Can be enabled        |
| `guest`    | Guest only                | Login, Register pages |

---

## Admin Panel (Filament)

Admin CRUD operations are handled by Filament Resources.
See [08-ADMIN-PANELS.md](./08-ADMIN-PANELS.md) for details.

Filament automatically provides:

-   Pagination
-   Search
-   Filters
-   Sorting
-   Bulk actions
-   Form validation
