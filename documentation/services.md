# Sukabaca - Business Logic & Services

## Service Layer Architecture

Services are located in `app/Services/` directory and contain business logic.

---

## 1. BorrowingService

**File**: `app/Services/BorrowingService.php`

Handles all borrowing-related business logic.

```php
<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BorrowingService
{
    // Default fee settings (can be moved to config or settings table)
    private float $rentalFeePerWeek = 5000.00;
    private float $lateFeePerDay = 2000.00;
    private int $maxBooksPerUser = 3;

    /**
     * Create a new borrowing
     */
    public function createBorrowing(User $user, Book $book, int $durationDays = 14): Borrowing
    {
        // Validate user hasn't exceeded limit
        $activeCount = $user->activeBorrowings()->count();
        if ($activeCount >= $this->maxBooksPerUser) {
            throw new \Exception("Maksimal peminjaman {$this->maxBooksPerUser} buku.");
        }

        // Find available copy
        $copy = $book->availableCopies()->first();
        if (!$copy) {
            throw new \Exception("Tidak ada copy tersedia untuk buku ini.");
        }

        return DB::transaction(function () use ($user, $copy, $durationDays) {
            // Create borrowing
            $borrowing = Borrowing::create([
                'borrowing_code' => Borrowing::generateBorrowingCode(),
                'user_id' => $user->id,
                'book_copy_id' => $copy->id,
                'borrowed_at' => now(),
                'due_date' => now()->addDays($durationDays),
                'rental_fee' => $this->calculateRentalFee($durationDays),
                'total_fee' => $this->calculateRentalFee($durationDays),
                'status' => Borrowing::STATUS_ACTIVE,
            ]);

            // Mark copy as borrowed
            $copy->markAsBorrowed();

            return $borrowing;
        });
    }

    /**
     * Return a book
     */
    public function returnBook(Borrowing $borrowing): Borrowing
    {
        if ($borrowing->status === Borrowing::STATUS_RETURNED) {
            throw new \Exception("Buku sudah dikembalikan.");
        }

        return DB::transaction(function () use ($borrowing) {
            $borrowing->returned_at = now();
            $borrowing->status = Borrowing::STATUS_RETURNED;
            $borrowing->days_late = $borrowing->calculateDaysLate();

            // Calculate late fee if overdue
            if ($borrowing->days_late > 0) {
                $borrowing->late_fee = $borrowing->days_late * $this->lateFeePerDay;
                $borrowing->total_fee = $borrowing->rental_fee + $borrowing->late_fee;
            }

            $borrowing->save();

            // Update copy status
            $borrowing->bookCopy->markAsAvailable();

            // Increment times borrowed
            $borrowing->bookCopy->book->incrementTimesBorrowed();

            return $borrowing;
        });
    }

    /**
     * Mark borrowing as paid
     */
    public function markAsPaid(Borrowing $borrowing): Borrowing
    {
        $borrowing->update(['is_paid' => true]);
        return $borrowing;
    }

    /**
     * Update overdue statuses (run via scheduler)
     */
    public function updateOverdueStatuses(): int
    {
        return Borrowing::where('status', Borrowing::STATUS_ACTIVE)
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => Borrowing::STATUS_OVERDUE]);
    }

    /**
     * Calculate rental fee
     */
    private function calculateRentalFee(int $days): float
    {
        $weeks = ceil($days / 7);
        return $weeks * $this->rentalFeePerWeek;
    }

    /**
     * Get borrowing statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_borrowings' => Borrowing::count(),
            'active_borrowings' => Borrowing::active()->count(),
            'overdue_borrowings' => Borrowing::overdue()->count(),
            'returned_today' => Borrowing::whereDate('returned_at', now())->count(),
            'total_unpaid' => Borrowing::unpaid()->sum('total_fee'),
        ];
    }
}
```

---

## 2. BookService

**File**: `app/Services/BookService.php`

Handles book management operations.

```php
<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Support\Facades\DB;

class BookService
{
    /**
     * Create book with copies
     */
    public function createBook(array $data, int $numberOfCopies = 1): Book
    {
        return DB::transaction(function () use ($data, $numberOfCopies) {
            $book = Book::create([
                'title' => $data['title'],
                'author' => $data['author'],
                'category_id' => $data['category_id'],
                'isbn' => $data['isbn'] ?? null,
                'description' => $data['description'] ?? null,
                'total_copies' => $numberOfCopies,
                'available_copies' => $numberOfCopies,
            ]);

            // Generate copies
            for ($i = 1; $i <= $numberOfCopies; $i++) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'copy_code' => BookCopy::generateCopyCode($book),
                    'status' => BookCopy::STATUS_AVAILABLE,
                ]);
            }

            return $book;
        });
    }

    /**
     * Add copies to existing book
     */
    public function addCopies(Book $book, int $count = 1): array
    {
        $copies = [];

        DB::transaction(function () use ($book, $count, &$copies) {
            for ($i = 0; $i < $count; $i++) {
                $copies[] = BookCopy::create([
                    'book_id' => $book->id,
                    'copy_code' => BookCopy::generateCopyCode($book),
                    'status' => BookCopy::STATUS_AVAILABLE,
                ]);
            }

            $book->updateCopyCounts();
        });

        return $copies;
    }

    /**
     * Update copy status
     */
    public function updateCopyStatus(BookCopy $copy, string $status, ?string $notes = null): BookCopy
    {
        if ($copy->isBorrowed() && $status !== BookCopy::STATUS_BORROWED) {
            throw new \Exception("Tidak bisa mengubah status copy yang sedang dipinjam.");
        }

        $copy->update([
            'status' => $status,
            'notes' => $notes ?? $copy->notes,
        ]);

        $copy->book->updateCopyCounts();

        return $copy;
    }

    /**
     * Get book statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_books' => Book::count(),
            'total_copies' => BookCopy::count(),
            'available_copies' => BookCopy::available()->count(),
            'borrowed_copies' => BookCopy::withStatus('borrowed')->count(),
            'maintenance_copies' => BookCopy::withStatus('maintenance')->count(),
            'lost_copies' => BookCopy::withStatus('lost')->count(),
        ];
    }

    /**
     * Get popular books
     */
    public function getPopularBooks(int $limit = 5)
    {
        return Book::with('category')
            ->orderBy('times_borrowed', 'desc')
            ->limit($limit)
            ->get();
    }
}
```

---

## 3. DashboardService

**File**: `app/Services/DashboardService.php`

Aggregates statistics for dashboard.

```php
<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\User;

class DashboardService
{
    public function __construct(
        private BookService $bookService,
        private BorrowingService $borrowingService
    ) {}

    /**
     * Get admin dashboard data
     */
    public function getAdminDashboard(): array
    {
        return [
            'stats' => [
                'total_books' => Book::count(),
                'total_copies' => BookCopy::count(),
                'available_copies' => BookCopy::available()->count(),
                'borrowed_copies' => BookCopy::withStatus('borrowed')->count(),
                'total_users' => User::where('role', 'user')->count(),
                'active_borrowings' => Borrowing::active()->count(),
                'overdue_borrowings' => Borrowing::overdue()->count(),
                'unpaid_amount' => Borrowing::unpaid()->sum('total_fee'),
            ],
            'recent_borrowings' => Borrowing::with(['user', 'bookCopy.book'])
                ->latest()
                ->limit(10)
                ->get(),
            'popular_books' => $this->bookService->getPopularBooks(5),
            'overdue_list' => Borrowing::overdue()
                ->with(['user', 'bookCopy.book'])
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Get user dashboard data
     */
    public function getUserDashboard(User $user): array
    {
        return [
            'active_borrowings' => Borrowing::forUser($user->id)
                ->whereIn('status', ['active', 'overdue'])
                ->with('bookCopy.book')
                ->get(),
            'borrowing_history' => Borrowing::forUser($user->id)
                ->where('status', 'returned')
                ->with('bookCopy.book')
                ->latest()
                ->limit(10)
                ->get(),
            'total_borrowed' => Borrowing::forUser($user->id)->count(),
            'unpaid_fees' => Borrowing::forUser($user->id)
                ->unpaid()
                ->sum('total_fee'),
        ];
    }
}
```

---

## Service Registration

Register services in `AppServiceProvider`:

```php
// app/Providers/AppServiceProvider.php

public function register(): void
{
    $this->app->singleton(BorrowingService::class);
    $this->app->singleton(BookService::class);
    $this->app->singleton(DashboardService::class);
}
```

---

## Using Services in Controllers

```php
// app/Http/Controllers/User/BorrowingController.php

class BorrowingController extends Controller
{
    public function __construct(
        private BorrowingService $borrowingService
    ) {}

    public function store(Request $request, Book $book)
    {
        try {
            $borrowing = $this->borrowingService->createBorrowing(
                auth()->user(),
                $book,
                $request->input('duration', 14)
            );

            return redirect()
                ->route('my-borrowings')
                ->with('success', "Berhasil meminjam buku. Kode: {$borrowing->borrowing_code}");

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
```

---

## Scheduled Tasks

Add to `routes/console.php`:

```php
use App\Services\BorrowingService;
use Illuminate\Support\Facades\Schedule;

// Update overdue statuses daily
Schedule::call(function () {
    app(BorrowingService::class)->updateOverdueStatuses();
})->dailyAt('00:00');
```
