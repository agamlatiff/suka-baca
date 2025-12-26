<?php

namespace App\Services;

use App\Contracts\Repositories\PaymentRepositoryInterface;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class PaymentService
{
  public function __construct(
    protected PaymentRepositoryInterface $paymentRepository
  ) {}

  public function getUserPayments(int $userId): Collection
  {
    return $this->paymentRepository->getByUser($userId);
  }

  public function getBorrowingPayments(int $borrowingId): Collection
  {
    return $this->paymentRepository->getByBorrowing($borrowingId);
  }

  public function getPendingPayments(): Collection
  {
    return $this->paymentRepository->getPending();
  }

  public function getVerifiedPayments(): Collection
  {
    return $this->paymentRepository->getVerified();
  }

  public function getPaymentByCode(string $code): ?Payment
  {
    return $this->paymentRepository->findByCode($code);
  }

  public function createPayment(array $data): Payment
  {
    $data['payment_code'] = 'PAY-' . strtoupper(Str::random(8));
    $data['status'] = $data['status'] ?? 'pending';

    return $this->paymentRepository->create($data);
  }

  public function verifyPayment(int $paymentId, int $verifiedBy): bool
  {
    $result = $this->paymentRepository->verify($paymentId, $verifiedBy);

    if ($result) {
      // Optionally auto-update borrowing as paid
      $payment = $this->paymentRepository->find($paymentId);
      if ($payment && $payment->borrowing) {
        $payment->borrowing->update(['is_paid' => true]);
      }
    }

    return $result;
  }

  public function rejectPayment(int $paymentId, int $verifiedBy, string $notes): bool
  {
    return $this->paymentRepository->reject($paymentId, $verifiedBy, $notes);
  }

  public function getRevenueByPeriod(string $startDate, string $endDate): float
  {
    return $this->paymentRepository->getRevenueByPeriod($startDate, $endDate);
  }

  public function getRevenueByMonth(int $year, int $month): float
  {
    return $this->paymentRepository->getRevenueByMonth($year, $month);
  }

  public function getRevenueStats(): array
  {
    $today = $this->getRevenueByPeriod(
      now()->startOfDay()->toDateTimeString(),
      now()->endOfDay()->toDateTimeString()
    );

    $thisWeek = $this->getRevenueByPeriod(
      now()->startOfWeek()->toDateTimeString(),
      now()->endOfWeek()->toDateTimeString()
    );

    $thisMonth = $this->getRevenueByMonth(now()->year, now()->month);

    return [
      'today' => $today,
      'week' => $thisWeek,
      'month' => $thisMonth,
    ];
  }

  /**
   * Get user payments filtered by status for frontend.
   */
  public function getUserPaymentsFiltered(int $userId, ?string $status = null, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
  {
    $query = Payment::where('user_id', $userId)
      ->with(['borrowing.bookCopy.book']);

    if ($status && $status !== 'all') {
      $dbStatus = $status === 'verified' ? 'confirmed' : $status;
      $query->where('status', $dbStatus);
    }

    return $query->latest()->paginate($perPage);
  }

  /**
   * Get payment counts for tabs.
   */
  public function getPaymentCounts(int $userId): array
  {
    $base = Payment::where('user_id', $userId);

    return [
      'all' => (clone $base)->count(),
      'pending' => (clone $base)->where('status', 'pending')->count(),
      'verified' => (clone $base)->where('status', 'confirmed')->count(),
      'rejected' => (clone $base)->where('status', 'rejected')->count(),
    ];
  }

  /**
   * Get total outstanding fees for user.
   */
  public function getTotalOutstandingFees(int $userId): float
  {
    return \App\Models\Borrowing::where('user_id', $userId)
      ->where('is_paid', false)
      ->where('total_fee', '>', 0)
      ->sum('total_fee');
  }
}
