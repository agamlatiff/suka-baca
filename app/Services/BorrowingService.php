<?php

namespace App\Services;

use App\Contracts\Repositories\BorrowingRepositoryInterface;
use App\Contracts\Repositories\BookRepositoryInterface;
use App\Models\Borrowing;
use App\Models\BookCopy;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BorrowingService
{
  public function __construct(
    protected BorrowingRepositoryInterface $borrowingRepository,
    protected BookRepositoryInterface $bookRepository
  ) {}

  public function getUserBorrowings(int $userId): Collection
  {
    return $this->borrowingRepository->getByUser($userId);
  }

  public function getUserBorrowingsPaginated(int $userId, int $perPage = 10): LengthAwarePaginator
  {
    return $this->borrowingRepository->paginateByUser($userId, $perPage);
  }

  public function getActiveBorrowings(): Collection
  {
    return $this->borrowingRepository->getActive();
  }

  public function getOverdueBorrowings(): Collection
  {
    return $this->borrowingRepository->getOverdue();
  }

  public function getPendingBorrowings(): Collection
  {
    return $this->borrowingRepository->getPending();
  }

  public function countUserActiveBorrowings(int $userId): int
  {
    return $this->borrowingRepository->countActiveByUser($userId);
  }

  public function canUserBorrow(int $userId): bool
  {
    $maxBooks = (int) Setting::get('max_books_per_user', 3);
    $activeCount = $this->countUserActiveBorrowings($userId);
    return $activeCount < $maxBooks;
  }

  public function createBorrowing(int $userId, int $bookCopyId, ?int $bookId = null): Borrowing
  {
    $rentalDays = (int) Setting::get('rental_duration_days', 7);
    $bookCopy = BookCopy::findOrFail($bookCopyId);
    $book = $bookCopy->book;

    $borrowing = $this->borrowingRepository->create([
      'borrowing_code' => 'BRW-' . strtoupper(Str::random(8)),
      'user_id' => $userId,
      'book_id' => $book->id,
      'book_copy_id' => $bookCopyId,
      'borrowed_at' => now(),
      'due_date' => now()->addDays($rentalDays),
      'rental_fee' => $book->rental_fee,
      'total_fee' => $book->rental_fee,
      'status' => 'pending',
      'is_paid' => false,
    ]);

    return $borrowing;
  }

  public function approveBorrowing(int $borrowingId): bool
  {
    $borrowing = $this->borrowingRepository->findOrFail($borrowingId);

    // Update book copy status
    $borrowing->bookCopy->update(['status' => 'borrowed']);

    // Update book availability
    $this->bookRepository->updateAvailability($borrowing->book_id);

    return $this->borrowingRepository->update($borrowingId, [
      'status' => 'active',
    ]);
  }

  public function rejectBorrowing(int $borrowingId, string $notes): bool
  {
    return $this->borrowingRepository->update($borrowingId, [
      'status' => 'rejected',
      'notes' => $notes,
    ]);
  }

  public function returnBook(int $borrowingId, string $condition = 'good', ?string $notes = null): array
  {
    $borrowing = $this->borrowingRepository->findOrFail($borrowingId);
    $now = Carbon::now();
    $dueDate = Carbon::parse($borrowing->due_date);

    // Calculate late fee
    $lateFee = 0;
    $daysLate = 0;
    if ($now->gt($dueDate)) {
      $daysLate = $now->diffInDays($dueDate);
      $lateFeePerDay = (int) Setting::get('late_fee_per_day', 1000);
      $lateFee = $daysLate * $lateFeePerDay;
    }

    // Calculate damage fee
    $damageFee = 0;
    if ($condition === 'damaged') {
      $damagePercentage = (int) Setting::get('damage_fee_percentage', 50);
      $damageFee = ($borrowing->book->book_price ?? 100000) * ($damagePercentage / 100);
    } elseif ($condition === 'lost') {
      $damageFee = $borrowing->book->book_price ?? 100000;
    }

    $totalFee = $borrowing->rental_fee + $lateFee + $damageFee;

    // Update borrowing
    $this->borrowingRepository->update($borrowingId, [
      'returned_at' => $now,
      'status' => 'returned',
      'return_condition' => $condition,
      'late_fee' => $lateFee,
      'damage_fee' => $damageFee,
      'total_fee' => $totalFee,
      'days_late' => $daysLate,
      'notes' => $notes,
    ]);

    // Update book copy status
    $newStatus = match ($condition) {
      'good' => 'available',
      'damaged' => 'damaged',
      'lost' => 'lost',
      default => 'available',
    };
    $borrowing->bookCopy->update(['status' => $newStatus]);

    // Update book availability
    $this->bookRepository->updateAvailability($borrowing->book_id);

    return [
      'late_fee' => $lateFee,
      'damage_fee' => $damageFee,
      'total_fee' => $totalFee,
      'days_late' => $daysLate,
    ];
  }

  public function markAsPaid(int $borrowingId): bool
  {
    return $this->borrowingRepository->update($borrowingId, [
      'is_paid' => true,
    ]);
  }

  public function getBorrowingStats(string $startDate, string $endDate): array
  {
    $borrowings = $this->borrowingRepository->getBetweenDates($startDate, $endDate);

    return [
      'total' => $borrowings->count(),
      'active' => $borrowings->where('status', 'active')->count(),
      'returned' => $borrowings->where('status', 'returned')->count(),
      'overdue' => $borrowings->where('status', 'overdue')->count(),
      'total_revenue' => $borrowings->sum('total_fee'),
      'total_late_fee' => $borrowings->sum('late_fee'),
    ];
  }
}
