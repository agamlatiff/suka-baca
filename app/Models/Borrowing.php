<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Borrowing extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'borrowing_code',
    'user_id',
    'book_copy_id',
    'borrowed_at',
    'due_date',
    'returned_at',
    'rental_fee',
    'late_fee',
    'damage_fee',
    'total_fee',
    'is_paid',
    'status',
    'return_condition',
    'days_late',
    'is_extended',
    'extension_date',
    'notes',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'borrowed_at' => 'date',
    'due_date' => 'date',
    'returned_at' => 'date',
    'extension_date' => 'date',
    'rental_fee' => 'decimal:2',
    'late_fee' => 'decimal:2',
    'damage_fee' => 'decimal:2',
    'total_fee' => 'decimal:2',
    'is_paid' => 'boolean',
    'is_extended' => 'boolean',
    'days_late' => 'integer',
  ];

  /**
   * Get the user that made the borrowing.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the book copy that was borrowed.
   */
  public function bookCopy(): BelongsTo
  {
    return $this->belongsTo(BookCopy::class);
  }

  /**
   * Scope to get only active borrowings.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope to get only overdue borrowings.
   */
  public function scopeOverdue($query)
  {
    return $query->where('status', 'overdue');
  }

  /**
   * Scope to get only returned borrowings.
   */
  public function scopeReturned($query)
  {
    return $query->where('status', 'returned');
  }

  /**
   * Scope to get only pending borrowings.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Get the payments for this borrowing.
   */
  public function payments(): HasMany
  {
    return $this->hasMany(Payment::class);
  }

  /**
   * Check if the borrowing is overdue.
   */
  public function isOverdue(): bool
  {
    return $this->status === 'active' && $this->due_date < Carbon::today();
  }

  /**
   * Calculate days remaining until due date.
   */
  public function getDaysRemainingAttribute(): int
  {
    if ($this->status !== 'active') {
      return 0;
    }
    return max(0, Carbon::today()->diffInDays($this->due_date, false));
  }

  /**
   * Process the return of the book.
   */
  public function processReturn(): bool
  {
    $today = Carbon::today();
    $daysLate = 0;
    $lateFee = 0;

    if ($today > $this->due_date) {
      $daysLate = $this->due_date->diffInDays($today);
      $lateFeePerDay = Setting::getLateFeePerDay();
      $lateFee = $daysLate * $lateFeePerDay;
    }

    // Update borrowing
    $this->update([
      'returned_at' => $today,
      'days_late' => $daysLate,
      'late_fee' => $lateFee,
      'total_fee' => $this->rental_fee + $lateFee,
      'status' => 'returned',
    ]);

    // Mark copy as available
    $this->bookCopy->markAsAvailable();

    // Increment book's times_borrowed and available_copies
    $book = $this->bookCopy->book;
    $book->increment('times_borrowed');
    $book->increment('available_copies');

    return true;
  }

  /**
   * Generate a unique borrowing code.
   */
  public static function generateBorrowingCode(): string
  {
    $date = Carbon::today()->format('Ymd');
    $count = static::whereDate('created_at', Carbon::today())->count() + 1;
    return sprintf('BRW-%s-%03d', $date, $count);
  }
}
