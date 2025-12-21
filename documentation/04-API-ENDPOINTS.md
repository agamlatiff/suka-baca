# Routes & Pages

## Route Overview

### Public Routes (Guest)

| Method | URI         | Name     | Description          |
| ------ | ----------- | -------- | -------------------- |
| GET    | `/`         | home     | Landing page         |
| GET    | `/login`    | login    | Login page           |
| GET    | `/register` | register | Registration page    |
| POST   | `/login`    | -        | Process login        |
| POST   | `/register` | -        | Process registration |

### User Routes (Authenticated)

| Method | URI                      | Name            | Description        |
| ------ | ------------------------ | --------------- | ------------------ |
| GET    | `/dashboard`             | dashboard       | User dashboard     |
| GET    | `/catalog`               | catalog.index   | Browse all books   |
| GET    | `/catalog/{book}`        | catalog.show    | Book detail page   |
| POST   | `/catalog/{book}/borrow` | catalog.borrow  | Borrow a book      |
| GET    | `/my-borrowings`         | borrowings.user | My borrowings list |
| POST   | `/logout`                | logout          | Logout             |

### Admin Routes (Admin Only)

| Method | URI                             | Name                    | Description     |
| ------ | ------------------------------- | ----------------------- | --------------- |
| GET    | `/admin`                        | admin.dashboard         | Admin dashboard |
| GET    | `/admin/books`                  | admin.books.index       | Book list       |
| GET    | `/admin/books/create`           | admin.books.create      | Add book form   |
| POST   | `/admin/books`                  | admin.books.store       | Save new book   |
| GET    | `/admin/books/{book}/edit`      | admin.books.edit        | Edit book form  |
| PUT    | `/admin/books/{book}`           | admin.books.update      | Update book     |
| DELETE | `/admin/books/{book}`           | admin.books.destroy     | Delete book     |
| GET    | `/admin/categories`             | admin.categories.index  | Category list   |
| GET    | `/admin/borrowings`             | admin.borrowings.index  | All borrowings  |
| PUT    | `/admin/borrowings/{id}/return` | admin.borrowings.return | Process return  |
| PUT    | `/admin/borrowings/{id}/paid`   | admin.borrowings.paid   | Mark as paid    |
| GET    | `/admin/users`                  | admin.users.index       | User list       |

---

## Route Definitions

### Web Routes (`routes/web.php`)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowingController;

// Public
Route::get('/', function () {
    return view('welcome');
});

// Authenticated Users
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Catalog
    Route::get('/catalog', [CatalogController::class, 'index'])
        ->name('catalog.index');
    Route::get('/catalog/{book}', [CatalogController::class, 'show'])
        ->name('catalog.show');
    Route::post('/catalog/{book}/borrow', [CatalogController::class, 'borrow'])
        ->name('catalog.borrow');

    // My Borrowings
    Route::get('/my-borrowings', [BorrowingController::class, 'userBorrowings'])
        ->name('borrowings.user');
});

require __DIR__.'/auth.php';
```

---

## Page Layouts

### User Pages

| Page          | View File                                   | Components                 |
| ------------- | ------------------------------------------- | -------------------------- |
| Dashboard     | `resources/views/dashboard.blade.php`       | Active borrowings, History |
| Catalog       | `resources/views/catalog/index.blade.php`   | Book grid, Search, Filters |
| Book Detail   | `resources/views/catalog/show.blade.php`    | Info, Borrow button        |
| My Borrowings | `resources/views/borrowings/user.blade.php` | List with status           |

### Admin Pages (Filament)

Admin panel is handled by Laravel Filament. See [08-ADMIN-PANELS.md](./08-ADMIN-PANELS.md) for details.

---

## URL Parameters

### Catalog Filters

```
/catalog?category=1&search=harry&available=1
```

| Parameter   | Type   | Description               |
| ----------- | ------ | ------------------------- |
| `category`  | int    | Filter by category ID     |
| `search`    | string | Search title/author       |
| `available` | bool   | Show only available books |

### Pagination

```
/catalog?page=2&per_page=12
```

| Parameter  | Type | Default | Description    |
| ---------- | ---- | ------- | -------------- |
| `page`     | int  | 1       | Current page   |
| `per_page` | int  | 12      | Items per page |

---

## API Endpoints (Optional)

If you need API access for mobile apps:

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/books', [ApiBookController::class, 'index']);
    Route::get('/books/{id}', [ApiBookController::class, 'show']);
    Route::get('/my-borrowings', [ApiBorrowingController::class, 'index']);
});
```

> **Note**: API is not part of MVP scope but can be added later.
