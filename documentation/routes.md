# Sukabaca - Routes & API Documentation

## Route Structure Overview

```
routes/
├── web.php      # Web routes (Blade views)
├── api.php      # API routes (JSON responses)
└── console.php  # Artisan commands & schedules
```

---

## Web Routes (routes/web.php)

### Public Routes

```php
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');
```

### Auth Routes (Laravel Breeze)

```php
require __DIR__.'/auth.php';
```

### Authenticated User Routes

```php
Route::middleware('auth')->group(function () {
    // User Dashboard
    Route::get('/dashboard', [User\DashboardController::class, 'index'])
        ->name('dashboard');

    // User Borrowings
    Route::get('/my-borrowings', [User\BorrowingController::class, 'index'])
        ->name('my-borrowings');
    Route::post('/borrow/{book}', [User\BorrowingController::class, 'store'])
        ->name('borrow');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});
```

### Admin Routes

```php
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    // Books Management
    Route::resource('books', Admin\BookController::class);
    Route::post('books/{book}/add-copies', [Admin\BookController::class, 'addCopies'])
        ->name('books.add-copies');

    // Book Copies
    Route::resource('book-copies', Admin\BookCopyController::class)
        ->only(['index', 'edit', 'update', 'destroy']);

    // Categories
    Route::resource('categories', Admin\CategoryController::class);

    // Borrowings
    Route::resource('borrowings', Admin\BorrowingController::class)
        ->only(['index', 'show']);
    Route::post('borrowings/{borrowing}/return', [Admin\BorrowingController::class, 'return'])
        ->name('borrowings.return');
    Route::post('borrowings/{borrowing}/mark-paid', [Admin\BorrowingController::class, 'markPaid'])
        ->name('borrowings.mark-paid');

    // Users
    Route::resource('users', Admin\UserController::class)
        ->only(['index', 'show', 'destroy']);
});
```

---

## Route List Summary

| Method     | URI                                       | Name                       | Controller                         |
| ---------- | ----------------------------------------- | -------------------------- | ---------------------------------- |
| **Public** |
| GET        | `/`                                       | home                       | -                                  |
| GET        | `/catalog`                                | catalog                    | CatalogController@index            |
| GET        | `/catalog/{book}`                         | catalog.show               | CatalogController@show             |
| **User**   |
| GET        | `/dashboard`                              | dashboard                  | User\DashboardController@index     |
| GET        | `/my-borrowings`                          | my-borrowings              | User\BorrowingController@index     |
| POST       | `/borrow/{book}`                          | borrow                     | User\BorrowingController@store     |
| **Admin**  |
| GET        | `/admin/dashboard`                        | admin.dashboard            | Admin\DashboardController@index    |
| GET        | `/admin/books`                            | admin.books.index          | Admin\BookController@index         |
| POST       | `/admin/books`                            | admin.books.store          | Admin\BookController@store         |
| GET        | `/admin/books/{book}`                     | admin.books.show           | Admin\BookController@show          |
| PUT        | `/admin/books/{book}`                     | admin.books.update         | Admin\BookController@update        |
| DELETE     | `/admin/books/{book}`                     | admin.books.destroy        | Admin\BookController@destroy       |
| POST       | `/admin/books/{book}/add-copies`          | admin.books.add-copies     | Admin\BookController@addCopies     |
| GET        | `/admin/categories`                       | admin.categories.index     | Admin\CategoryController@index     |
| POST       | `/admin/categories`                       | admin.categories.store     | Admin\CategoryController@store     |
| PUT        | `/admin/categories/{category}`            | admin.categories.update    | Admin\CategoryController@update    |
| DELETE     | `/admin/categories/{category}`            | admin.categories.destroy   | Admin\CategoryController@destroy   |
| GET        | `/admin/borrowings`                       | admin.borrowings.index     | Admin\BorrowingController@index    |
| GET        | `/admin/borrowings/{borrowing}`           | admin.borrowings.show      | Admin\BorrowingController@show     |
| POST       | `/admin/borrowings/{borrowing}/return`    | admin.borrowings.return    | Admin\BorrowingController@return   |
| POST       | `/admin/borrowings/{borrowing}/mark-paid` | admin.borrowings.mark-paid | Admin\BorrowingController@markPaid |
| GET        | `/admin/users`                            | admin.users.index          | Admin\UserController@index         |

---

## API Routes (Optional - routes/api.php)

If you need API for mobile app or SPA:

```php
Route::prefix('v1')->group(function () {
    // Public
    Route::get('/books', [Api\BookController::class, 'index']);
    Route::get('/books/{book}', [Api\BookController::class, 'show']);
    Route::get('/categories', [Api\CategoryController::class, 'index']);

    // Auth required
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', fn(Request $request) => $request->user());
        Route::get('/my-borrowings', [Api\BorrowingController::class, 'index']);
        Route::post('/borrow/{book}', [Api\BorrowingController::class, 'store']);
    });
});
```

---

## Middleware

### CheckRole Middleware

**File**: `app/Http/Middleware/CheckRole.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

### Register in bootstrap/app.php

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

---

## Named Routes Usage

### In Blade Templates

```blade
<a href="{{ route('catalog.show', $book) }}">{{ $book->title }}</a>

<form action="{{ route('borrow', $book) }}" method="POST">
    @csrf
    <button type="submit">Pinjam</button>
</form>

<a href="{{ route('admin.books.edit', $book) }}">Edit</a>
```

### In Controllers

```php
return redirect()->route('dashboard');
return redirect()->route('admin.borrowings.show', $borrowing);
return redirect()->route('catalog.show', ['book' => $book->id]);
```

---

## Form Requests

Create form requests for validation:

### StoreBookRequest

```php
// app/Http/Requests/StoreBookRequest.php

public function rules(): array
{
    return [
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'isbn' => 'nullable|string|max:20|unique:books,isbn',
        'description' => 'nullable|string',
        'copies' => 'required|integer|min:1|max:100',
    ];
}
```

### StoreBorrowingRequest

```php
// app/Http/Requests/StoreBorrowingRequest.php

public function rules(): array
{
    return [
        'duration' => 'required|in:7,14',
    ];
}
```
