<?php

namespace App\Contracts\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
  public function getByUser(int $userId): Collection;

  public function getByBorrowing(int $borrowingId): Collection;

  public function getPending(): Collection;

  public function getVerified(): Collection;

  public function findByCode(string $code): ?Payment;

  public function verify(int $paymentId, int $verifiedBy): bool;

  public function reject(int $paymentId, int $verifiedBy, string $notes): bool;

  public function getRevenueByPeriod(string $startDate, string $endDate): float;

  public function getRevenueByMonth(int $year, int $month): float;
}
