<?php

namespace App\Services;

use App\Models\User;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Setting;
use Illuminate\Support\Collection;

class BorrowingValidationService
{
  /**
   * Validate if user can borrow a book.
   * Returns array with 'valid' boolean and 'errors' array.
   */
  public function canBorrow(int $userId, ?int $bookId = null): array
  {
    $errors = new Collection();

    // Check user status
    $userCheck = $this->checkUserStatus($userId);
    if (!$userCheck['valid']) {
      $errors->push($userCheck['error']);
    }

    // Check active borrowing limit
    $limitCheck = $this->checkBorrowingLimit($userId);
    if (!$limitCheck['valid']) {
      $errors->push($limitCheck['error']);
    }

    // Check outstanding payments
    $paymentCheck = $this->checkOutstandingPayments($userId);
    if (!$paymentCheck['valid']) {
      $errors->push($paymentCheck['error']);
    }

    // Check book availability (if book specified)
    if ($bookId) {
      $availabilityCheck = $this->checkBookAvailability($bookId);
      if (!$availabilityCheck['valid']) {
        $errors->push($availabilityCheck['error']);
      }
    }

    return [
      'valid' => $errors->isEmpty(),
      'errors' => $errors->toArray(),
    ];
  }

  /**
   * Check if user account is active.
   */
  public function checkUserStatus(int $userId): array
  {
    $user = User::find($userId);

    if (!$user) {
      return [
        'valid' => false,
        'error' => [
          'code' => 'USER_NOT_FOUND',
          'message' => 'User tidak ditemukan.',
        ],
      ];
    }

    if ($user->status === 'suspended') {
      return [
        'valid' => false,
        'error' => [
          'code' => 'USER_SUSPENDED',
          'message' => 'Akun Anda sedang dinonaktifkan. Hubungi admin untuk informasi lebih lanjut.',
        ],
      ];
    }

    return ['valid' => true];
  }

  /**
   * Check if user has reached maximum active borrowings limit.
   */
  public function checkBorrowingLimit(int $userId): array
  {
    $maxBooks = (int) Setting::get('max_books_per_user', 3);

    $activeBorrowings = User::find($userId)
      ?->borrowings()
      ->whereIn('status', ['active', 'pending'])
      ->count() ?? 0;

    if ($activeBorrowings >= $maxBooks) {
      return [
        'valid' => false,
        'error' => [
          'code' => 'LIMIT_REACHED',
          'message' => "Anda sudah mencapai batas maksimal peminjaman ({$maxBooks} buku).",
          'current' => $activeBorrowings,
          'max' => $maxBooks,
        ],
      ];
    }

    return [
      'valid' => true,
      'current' => $activeBorrowings,
      'max' => $maxBooks,
      'remaining' => $maxBooks - $activeBorrowings,
    ];
  }

  /**
   * Check if user has outstanding (unpaid) payments.
   */
  public function checkOutstandingPayments(int $userId): array
  {
    $outstandingAmount = User::find($userId)
      ?->borrowings()
      ->where('is_paid', false)
      ->where('status', 'returned')
      ->sum('total_fee') ?? 0;

    // Also check for overdue borrowings
    $overdueCount = User::find($userId)
      ?->borrowings()
      ->where('status', 'overdue')
      ->count() ?? 0;

    if ($outstandingAmount > 0) {
      return [
        'valid' => false,
        'error' => [
          'code' => 'OUTSTANDING_PAYMENT',
          'message' => 'Anda memiliki tunggakan yang belum dibayar sebesar Rp ' . number_format($outstandingAmount, 0, ',', '.'),
          'amount' => $outstandingAmount,
        ],
      ];
    }

    if ($overdueCount > 0) {
      return [
        'valid' => false,
        'error' => [
          'code' => 'OVERDUE_BORROWINGS',
          'message' => "Anda memiliki {$overdueCount} peminjaman yang terlambat. Harap kembalikan terlebih dahulu.",
          'count' => $overdueCount,
        ],
      ];
    }

    return ['valid' => true];
  }

  /**
   * Check if book has available copies.
   */
  public function checkBookAvailability(int $bookId): array
  {
    $book = Book::find($bookId);

    if (!$book) {
      return [
        'valid' => false,
        'error' => [
          'code' => 'BOOK_NOT_FOUND',
          'message' => 'Buku tidak ditemukan.',
        ],
      ];
    }

    if ($book->available_copies <= 0) {
      return [
        'valid' => false,
        'error' => [
          'code' => 'NO_COPIES_AVAILABLE',
          'message' => 'Maaf, tidak ada eksemplar yang tersedia untuk buku ini.',
          'total_copies' => $book->total_copies,
          'available_copies' => $book->available_copies,
        ],
      ];
    }

    // Get first available copy
    $availableCopy = BookCopy::where('book_id', $bookId)
      ->where('status', 'available')
      ->first();

    return [
      'valid' => true,
      'available_copies' => $book->available_copies,
      'available_copy_id' => $availableCopy?->id,
    ];
  }

  /**
   * Check if user can extend a borrowing.
   */
  public function canExtend(int $borrowingId): array
  {
    $borrowing = \App\Models\Borrowing::find($borrowingId);

    if (!$borrowing) {
      return [
        'valid' => false,
        'error' => [
          'code' => 'BORROWING_NOT_FOUND',
          'message' => 'Peminjaman tidak ditemukan.',
        ],
      ];
    }

    // Check if already extended
    if ($borrowing->is_extended) {
      return [
        'valid' => false,
        'error' => [
          'code' => 'ALREADY_EXTENDED',
          'message' => 'Peminjaman ini sudah diperpanjang sebelumnya. Maksimal 1x perpanjangan.',
        ],
      ];
    }

    // Check if overdue
    if ($borrowing->status === 'overdue' || now()->gt($borrowing->due_date)) {
      return [
        'valid' => false,
        'error' => [
          'code' => 'CANNOT_EXTEND_OVERDUE',
          'message' => 'Peminjaman yang sudah terlambat tidak dapat diperpanjang.',
        ],
      ];
    }

    // Check if returned
    if ($borrowing->status === 'returned') {
      return [
        'valid' => false,
        'error' => [
          'code' => 'ALREADY_RETURNED',
          'message' => 'Peminjaman ini sudah dikembalikan.',
        ],
      ];
    }

    return ['valid' => true];
  }

  /**
   * Get user borrowing summary for display.
   */
  public function getUserBorrowingSummary(int $userId): array
  {
    $user = User::find($userId);

    if (!$user) {
      return [];
    }

    $limitCheck = $this->checkBorrowingLimit($userId);

    return [
      'active_count' => $user->borrowings()->where('status', 'active')->count(),
      'pending_count' => $user->borrowings()->where('status', 'pending')->count(),
      'overdue_count' => $user->borrowings()->where('status', 'overdue')->count(),
      'max_allowed' => $limitCheck['max'] ?? 3,
      'remaining_slots' => $limitCheck['remaining'] ?? 0,
      'outstanding_fee' => $user->borrowings()->where('is_paid', false)->sum('total_fee'),
      'can_borrow' => $this->canBorrow($userId)['valid'],
    ];
  }
}
