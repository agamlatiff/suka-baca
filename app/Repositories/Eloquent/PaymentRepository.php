<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\PaymentRepositoryInterface;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
  public function __construct(Payment $model)
  {
    parent::__construct($model);
  }

  public function getByUser(int $userId): Collection
  {
    return $this->model
      ->where('user_id', $userId)
      ->with(['borrowing'])
      ->latest()
      ->get();
  }

  public function getByBorrowing(int $borrowingId): Collection
  {
    return $this->model
      ->where('borrowing_id', $borrowingId)
      ->latest()
      ->get();
  }

  public function getPending(): Collection
  {
    return $this->model
      ->where('status', 'pending')
      ->with(['user', 'borrowing'])
      ->latest()
      ->get();
  }

  public function getVerified(): Collection
  {
    return $this->model
      ->where('status', 'verified')
      ->with(['user', 'borrowing'])
      ->latest()
      ->get();
  }

  public function findByCode(string $code): ?Payment
  {
    return $this->model->where('payment_code', $code)->first();
  }

  public function verify(int $paymentId, int $verifiedBy): bool
  {
    return $this->update($paymentId, [
      'status' => 'verified',
      'verified_by' => $verifiedBy,
      'verified_at' => now(),
    ]);
  }

  public function reject(int $paymentId, int $verifiedBy, string $notes): bool
  {
    return $this->update($paymentId, [
      'status' => 'rejected',
      'verified_by' => $verifiedBy,
      'verified_at' => now(),
      'rejection_notes' => $notes,
    ]);
  }

  public function getRevenueByPeriod(string $startDate, string $endDate): float
  {
    return (float) $this->model
      ->where('status', 'verified')
      ->whereBetween('created_at', [$startDate, $endDate])
      ->sum('amount');
  }

  public function getRevenueByMonth(int $year, int $month): float
  {
    return (float) $this->model
      ->where('status', 'verified')
      ->whereYear('created_at', $year)
      ->whereMonth('created_at', $month)
      ->sum('amount');
  }
}
