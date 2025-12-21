# Sukabaca - Controllers Documentation

## Directory Structure

```
app/Http/Controllers/
├── Controller.php              # Base controller
├── ProfileController.php       # User profile
├── CatalogController.php       # Public catalog
├── Admin/
│   ├── DashboardController.php
│   ├── BookController.php
│   ├── CategoryController.php
│   ├── BookCopyController.php
│   ├── BorrowingController.php
│   └── UserController.php
└── User/
    ├── DashboardController.php
    └── BorrowingController.php
```

---

## 1. CatalogController (Public)

**File**: `app/Http/Controllers/CatalogController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display catalog with filters
     */
    public function index(Request $request)
    {
        $query = Book::with('category')->where('available_copies', '>', 0);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sort
        $sortBy = $request->get('sort', 'title');
        $sortDir = $request->get('dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $books = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('catalog.index', compact('books', 'categories'));
    }

    /**
     * Display book detail
     */
    public function show(Book $book)
    {
        $book->load(['category', 'copies' => function ($q) {
            $q->where('status', 'available');
        }]);

        return view('catalog.show', compact('book'));
    }
}
```

---

## 2. Admin Controllers

### Admin\DashboardController

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index()
    {
        $data = $this->dashboardService->getAdminDashboard();
        return view('admin.dashboard', $data);
    }
}
```

### Admin\CategoryController

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(Category $category)
    {
        if ($category->books()->exists()) {
            return back()->with('error', 'Tidak bisa hapus kategori yang masih memiliki buku.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
```

### Admin\BookController

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Services\BookService;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    public function __construct(
        private BookService $bookService
    ) {}

    public function index()
    {
        $books = Book::with('category')
            ->withCount('copies')
            ->orderBy('title')
            ->paginate(20);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->bookService->createBook(
            $request->validated(),
            $request->input('copies', 1)
        );

        return redirect()->route('admin.books.show', $book)
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'copies.activeBorrowing.user']);
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());
        return redirect()->route('admin.books.show', $book)
            ->with('success', 'Buku berhasil diupdate.');
    }

    public function destroy(Book $book)
    {
        if ($book->copies()->whereHas('activeBorrowing')->exists()) {
            return back()->with('error', 'Tidak bisa hapus buku yang sedang dipinjam.');
        }

        $book->delete();
        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }

    public function addCopies(Book $book)
    {
        $count = request('count', 1);
        $this->bookService->addCopies($book, $count);

        return back()->with('success', "{$count} copy berhasil ditambahkan.");
    }
}
```

### Admin\BorrowingController

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function __construct(
        private BorrowingService $borrowingService
    ) {}

    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'bookCopy.book']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter unpaid
        if ($request->boolean('unpaid')) {
            $query->where('is_paid', false);
        }

        $borrowings = $query->latest()->paginate(20);

        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'bookCopy.book']);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function return(Borrowing $borrowing)
    {
        try {
            $this->borrowingService->returnBook($borrowing);
            return back()->with('success', 'Buku berhasil dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function markPaid(Borrowing $borrowing)
    {
        $this->borrowingService->markAsPaid($borrowing);
        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }
}
```

### Admin\UserController

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')
            ->withCount(['borrowings', 'activeBorrowings'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['borrowings' => fn($q) => $q->latest()->limit(10)]);
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if ($user->activeBorrowings()->exists()) {
            return back()->with('error', 'Tidak bisa hapus user yang masih pinjam buku.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
```

---

## 3. User Controllers

### User\DashboardController

```php
<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index()
    {
        $data = $this->dashboardService->getUserDashboard(auth()->user());
        return view('user.dashboard', $data);
    }
}
```

### User\BorrowingController

```php
<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use App\Http\Requests\StoreBorrowingRequest;

class BorrowingController extends Controller
{
    public function __construct(
        private BorrowingService $borrowingService
    ) {}

    public function index()
    {
        $borrowings = Borrowing::forUser(auth()->id())
            ->with('bookCopy.book')
            ->latest()
            ->paginate(20);

        return view('user.borrowings.index', compact('borrowings'));
    }

    public function store(StoreBorrowingRequest $request, Book $book)
    {
        try {
            $borrowing = $this->borrowingService->createBorrowing(
                auth()->user(),
                $book,
                $request->input('duration', 14)
            );

            return redirect()->route('my-borrowings')
                ->with('success', "Berhasil meminjam buku. Kode: {$borrowing->borrowing_code}");

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
```

---

## 4. Form Requests

### StoreCategoryRequest

```php
// app/Http/Requests/StoreCategoryRequest.php

public function rules(): array
{
    return [
        'name' => 'required|string|max:100|unique:categories,name',
        'description' => 'nullable|string|max:1000',
    ];
}
```

### UpdateCategoryRequest

```php
// app/Http/Requests/UpdateCategoryRequest.php

public function rules(): array
{
    return [
        'name' => 'required|string|max:100|unique:categories,name,' . $this->category->id,
        'description' => 'nullable|string|max:1000',
    ];
}
```

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

### UpdateBookRequest

```php
// app/Http/Requests/UpdateBookRequest.php

public function rules(): array
{
    return [
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $this->book->id,
        'description' => 'nullable|string',
    ];
}
```

### StoreBorrowingRequest

```php
// app/Http/Requests/StoreBorrowingRequest.php

public function rules(): array
{
    return [
        'duration' => 'required|integer|in:7,14',
    ];
}
```

---

## Response Patterns

### Flash Messages

```php
// Success
return redirect()->route('...')->with('success', 'Message');

// Error
return back()->with('error', 'Error message');

// With input (validation failed)
return back()->withInput()->withErrors($validator);
```

### Blade Flash Display

```blade
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
```
