# Sukabaca - Eloquent Models Documentation

## Overview

All models are located in `app/Models/` directory.

---

## 1. User Model

**File**: `app/Models/User.php`

### Fillable Attributes

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'phone',
];
```

### Hidden Attributes

```php
protected $hidden = [
    'password',
    'remember_token',
];
```

### Casts

```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

### Relationships

```php
// User has many borrowings
public function borrowings(): HasMany
{
    return $this->hasMany(Borrowing::class);
}

// Active borrowings only
public function activeBorrowings(): HasMany
{
    return $this->hasMany(Borrowing::class)
        ->whereIn('status', ['active', 'overdue']);
}
```

### Accessors & Helpers

```php
// Check if user is admin
public function isAdmin(): bool
{
    return $this->role === 'admin';
}

// Get active borrowing count
public function getActiveBorrowingCountAttribute(): int
{
    return $this->activeBorrowings()->count();
}
```

---

## 2. Category Model

**File**: `app/Models/Category.php`

### Fillable Attributes

```php
protected $fillable = [
    'name',
    'description',
];
```

### Relationships

```php
// Category has many books
public function books(): HasMany
{
    return $this->hasMany(Book::class);
}
```

### Accessors

```php
// Get total books count
public function getBooksCountAttribute(): int
{
    return $this->books()->count();
}
```

---

## 3. Book Model

**File**: `app/Models/Book.php`

### Fillable Attributes

```php
protected $fillable = [
    'title',
    'author',
    'category_id',
    'isbn',
    'description',
    'total_copies',
    'available_copies',
    'times_borrowed',
];
```

### Relationships

```php
// Book belongs to category
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}

// Book has many copies
public function copies(): HasMany
{
    return $this->hasMany(BookCopy::class);
}

// Only available copies
public function availableCopies(): HasMany
{
    return $this->hasMany(BookCopy::class)
        ->where('status', 'available');
}
```

### Scopes

```php
// Books with available copies
public function scopeAvailable(Builder $query): Builder
{
    return $query->where('available_copies', '>', 0);
}

// Search by title or author
public function scopeSearch(Builder $query, string $term): Builder
{
    return $query->where(function ($q) use ($term) {
        $q->where('title', 'like', "%{$term}%")
          ->orWhere('author', 'like', "%{$term}%");
    });
}

// Filter by category
public function scopeInCategory(Builder $query, int $categoryId): Builder
{
    return $query->where('category_id', $categoryId);
}
```

### Methods

```php
// Check if book is available
public function isAvailable(): bool
{
    return $this->available_copies > 0;
}

// Increment times borrowed counter
public function incrementTimesBorrowed(): void
{
    $this->increment('times_borrowed');
}

// Update copy counts
public function updateCopyCounts(): void
{
    $this->total_copies = $this->copies()->count();
    $this->available_copies = $this->copies()
        ->where('status', 'available')
        ->count();
    $this->save();
}
```

---

## 4. BookCopy Model

**File**: `app/Models/BookCopy.php`

### Fillable Attributes

```php
protected $fillable = [
    'book_id',
    'copy_code',
    'status',
    'notes',
];
```

### Status Constants

```php
const STATUS_AVAILABLE = 'available';
const STATUS_BORROWED = 'borrowed';
const STATUS_MAINTENANCE = 'maintenance';
const STATUS_LOST = 'lost';

const STATUSES = [
    self::STATUS_AVAILABLE,
    self::STATUS_BORROWED,
    self::STATUS_MAINTENANCE,
    self::STATUS_LOST,
];
```

### Relationships

```php
// Copy belongs to book
public function book(): BelongsTo
{
    return $this->belongsTo(Book::class);
}

// Copy has many borrowings (history)
public function borrowings(): HasMany
{
    return $this->hasMany(Borrowing::class);
}

// Current active borrowing
public function activeBorrowing(): HasOne
{
    return $this->hasOne(Borrowing::class)
        ->whereIn('status', ['active', 'overdue']);
}
```

### Scopes

```php
// Available copies only
public function scopeAvailable(Builder $query): Builder
{
    return $query->where('status', self::STATUS_AVAILABLE);
}

// Filter by status
public function scopeWithStatus(Builder $query, string $status): Builder
{
    return $query->where('status', $status);
}
```

### Methods

```php
// Mark as borrowed
public function markAsBorrowed(): void
{
    $this->update(['status' => self::STATUS_BORROWED]);
    $this->book->updateCopyCounts();
}

// Mark as available
public function markAsAvailable(): void
{
    $this->update(['status' => self::STATUS_AVAILABLE]);
    $this->book->updateCopyCounts();
}

// Check if currently borrowed
public function isBorrowed(): bool
{
    return $this->status === self::STATUS_BORROWED;
}

// Check if available
public function isAvailable(): bool
{
    return $this->status === self::STATUS_AVAILABLE;
}

// Generate copy code
public static function generateCopyCode(Book $book): string
{
    $bookCode = 'BK' . str_pad($book->id, 3, '0', STR_PAD_LEFT);
    $copyNumber = $book->copies()->count() + 1;
    return $bookCode . '-C' . str_pad($copyNumber, 2, '0', STR_PAD_LEFT);
}
```

---

## 5. Borrowing Model

**File**: `app/Models/Borrowing.php`

### Fillable Attributes

```php
protected $fillable = [
    'borrowing_code',
    'user_id',
    'book_copy_id',
    'borrowed_at',
    'due_date',
    'returned_at',
    'rental_fee',
    'late_fee',
    'total_fee',
    'is_paid',
    'status',
    'days_late',
];
```

### Casts

```php
protected function casts(): array
{
    return [
        'borrowed_at' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date',
        'rental_fee' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'is_paid' => 'boolean',
    ];
}
```

### Status Constants

```php
const STATUS_ACTIVE = 'active';
const STATUS_RETURNED = 'returned';
const STATUS_OVERDUE = 'overdue';
```

### Relationships

```php
// Borrowing belongs to user
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

// Borrowing belongs to book copy
public function bookCopy(): BelongsTo
{
    return $this->belongsTo(BookCopy::class);
}

// Get book through copy (convenience)
public function book(): HasOneThrough
{
    return $this->hasOneThrough(
        Book::class,
        BookCopy::class,
        'id',        // book_copies.id
        'id',        // books.id
        'book_copy_id', // borrowings.book_copy_id
        'book_id'    // book_copies.book_id
    );
}
```

### Scopes

```php
// Active borrowings
public function scopeActive(Builder $query): Builder
{
    return $query->where('status', self::STATUS_ACTIVE);
}

// Overdue borrowings
public function scopeOverdue(Builder $query): Builder
{
    return $query->where('status', self::STATUS_ACTIVE)
        ->where('due_date', '<', now()->toDateString());
}

// Unpaid borrowings
public function scopeUnpaid(Builder $query): Builder
{
    return $query->where('is_paid', false);
}

// Filter by user
public function scopeForUser(Builder $query, int $userId): Builder
{
    return $query->where('user_id', $userId);
}
```

### Methods

```php
// Generate borrowing code
public static function generateBorrowingCode(): string
{
    $date = now()->format('Ymd');
    $count = self::whereDate('created_at', now())->count() + 1;
    return 'BRW-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
}

// Calculate late days
public function calculateDaysLate(): int
{
    if ($this->returned_at) {
        return max(0, $this->returned_at->diffInDays($this->due_date, false));
    }

    if (now()->gt($this->due_date)) {
        return now()->diffInDays($this->due_date);
    }

    return 0;
}

// Check if overdue
public function isOverdue(): bool
{
    return $this->status === self::STATUS_ACTIVE
        && now()->gt($this->due_date);
}

// Mark as returned
public function markAsReturned(): void
{
    $this->returned_at = now();
    $this->status = self::STATUS_RETURNED;
    $this->days_late = $this->calculateDaysLate();
    $this->save();

    $this->bookCopy->markAsAvailable();
    $this->bookCopy->book->incrementTimesBorrowed();
}

// Calculate fees
public function calculateFees(float $rentalPerWeek, float $lateFeePerDay): void
{
    // Calculate rental fee based on duration
    $weeks = ceil($this->borrowed_at->diffInDays($this->due_date) / 7);
    $this->rental_fee = $weeks * $rentalPerWeek;

    // Calculate late fee
    $this->late_fee = $this->days_late * $lateFeePerDay;

    // Total
    $this->total_fee = $this->rental_fee + $this->late_fee;

    $this->save();
}

// Mark as paid
public function markAsPaid(): void
{
    $this->update(['is_paid' => true]);
}
```

---

## Model Observers (Optional)

Create observers in `app/Observers/` for automatic actions:

### BookCopyObserver

```php
// app/Observers/BookCopyObserver.php
class BookCopyObserver
{
    public function created(BookCopy $copy): void
    {
        $copy->book->updateCopyCounts();
    }

    public function deleted(BookCopy $copy): void
    {
        $copy->book->updateCopyCounts();
    }

    public function updated(BookCopy $copy): void
    {
        if ($copy->isDirty('status')) {
            $copy->book->updateCopyCounts();
        }
    }
}
```

Register in `AppServiceProvider`:

```php
public function boot(): void
{
    BookCopy::observe(BookCopyObserver::class);
}
```

---

## Usage Examples

### Get available books

```php
$books = Book::available()
    ->with('category')
    ->orderBy('title')
    ->paginate(20);
```

### Search books

```php
$books = Book::search($request->q)
    ->inCategory($request->category_id)
    ->available()
    ->get();
```

### Create borrowing

```php
$copy = BookCopy::available()
    ->where('book_id', $bookId)
    ->firstOrFail();

$borrowing = Borrowing::create([
    'borrowing_code' => Borrowing::generateBorrowingCode(),
    'user_id' => auth()->id(),
    'book_copy_id' => $copy->id,
    'borrowed_at' => now(),
    'due_date' => now()->addDays(14),
    'status' => Borrowing::STATUS_ACTIVE,
]);

$copy->markAsBorrowed();
```

### Return book

```php
$borrowing = Borrowing::findOrFail($id);
$borrowing->markAsReturned();
$borrowing->calculateFees(5000, 2000); // Rp 5k/week, Rp 2k/day late
```

### Get user's active borrowings

```php
$borrowings = Borrowing::forUser(auth()->id())
    ->active()
    ->with(['bookCopy.book'])
    ->get();
```

### Check overdue books

```php
$overdue = Borrowing::overdue()
    ->with(['user', 'bookCopy.book'])
    ->get();
```
