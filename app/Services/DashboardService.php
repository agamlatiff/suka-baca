<?php

namespace App\Services;

use App\Contracts\Repositories\BookRepositoryInterface;
use App\Contracts\Repositories\BorrowingRepositoryInterface;
use App\Contracts\Repositories\PaymentRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;

class DashboardService
{
  public function __construct(
    protected BookRepositoryInterface $bookRepository,
    protected BorrowingRepositoryInterface $borrowingRepository,
    protected PaymentRepositoryInterface $paymentRepository,
    protected UserRepositoryInterface $userRepository
  ) {}

  public function getStats(): array
  {
    return [
      'total_books' => $this->bookRepository->all()->count(),
      'available_books' => $this->bookRepository->getAvailable()->count(),
      'total_members' => $this->userRepository->getMembers()->count(),
      'active_borrowings' => $this->borrowingRepository->getActive()->count(),
      'overdue_borrowings' => $this->borrowingRepository->getOverdue()->count(),
      'pending_borrowings' => $this->borrowingRepository->getPending()->count(),
      'pending_payments' => $this->paymentRepository->getPending()->count(),
    ];
  }

  public function getRevenueStats(): array
  {
    $today = $this->paymentRepository->getRevenueByPeriod(
      now()->startOfDay()->toDateTimeString(),
      now()->endOfDay()->toDateTimeString()
    );

    $thisWeek = $this->paymentRepository->getRevenueByPeriod(
      now()->startOfWeek()->toDateTimeString(),
      now()->endOfWeek()->toDateTimeString()
    );

    $thisMonth = $this->paymentRepository->getRevenueByMonth(now()->year, now()->month);

    return [
      'today' => $today,
      'week' => $thisWeek,
      'month' => $thisMonth,
    ];
  }

  public function getPopularBooks(int $limit = 5): \Illuminate\Database\Eloquent\Collection
  {
    return $this->bookRepository->getPopular($limit);
  }

  public function getRecentBorrowings(int $limit = 10): \Illuminate\Database\Eloquent\Collection
  {
    return $this->borrowingRepository->getActive()->take($limit);
  }

  public function getTopBorrowers(int $limit = 10, ?string $startDate = null, ?string $endDate = null): \Illuminate\Database\Eloquent\Collection
  {
    return $this->userRepository->getTopBorrowers($limit, $startDate, $endDate);
  }

  public function getMonthlyRevenue(int $months = 12): array
  {
    $data = [];
    for ($i = $months - 1; $i >= 0; $i--) {
      $date = now()->subMonths($i);
      $data[] = [
        'month' => $date->translatedFormat('M Y'),
        'revenue' => $this->paymentRepository->getRevenueByMonth($date->year, $date->month),
      ];
    }
    return $data;
  }

  /**
   * Get user dashboard data (for user frontend).
   */
  public function getUserDashboardData(int $userId): array
  {
    $user = $this->userRepository->find($userId);

    // Active borrowings (including overdue)
    $activeBorrowings = $user->borrowings()
      ->with(['bookCopy.book' => fn($q) => $q->select('id', 'title', 'author', 'image', 'slug')])
      ->whereIn('status', ['active', 'overdue'])
      ->orderBy('due_date', 'asc')
      ->get();

    // Pending borrowings
    $pendingBorrowings = $user->borrowings()
      ->with(['bookCopy.book'])
      ->where('status', 'pending')
      ->latest()
      ->get();

    // Overdue borrowings for alert
    $overdueBorrowings = $activeBorrowings->filter(
      fn($b) => $b->is_overdue || ($b->status === 'active' && $b->due_date < now())
    );

    // Unpaid bills
    $unpaidBorrowings = $user->borrowings()
      ->where('is_paid', false)
      ->where('total_fee', '>', 0)
      ->get();

    return [
      'user' => $user,
      'activeBorrowings' => $activeBorrowings,
      'pendingBorrowings' => $pendingBorrowings,
      'overdueBorrowings' => $overdueBorrowings,
      'unpaidBorrowings' => $unpaidBorrowings,
      'stats' => [
        'active_count' => $activeBorrowings->count(),
        'total_borrowed' => $user->borrowings()->count(),
        'wishlist_count' => $user->wishlists()->count(),
        'total_outstanding_fees' => $unpaidBorrowings->sum('total_fee'),
      ],
    ];
  }
}
