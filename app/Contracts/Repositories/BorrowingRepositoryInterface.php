<?php

namespace App\Contracts\Repositories;

use App\Models\Borrowing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BorrowingRepositoryInterface extends BaseRepositoryInterface
{
  public function getByUser(int $userId): Collection;

  public function getActive(): Collection;

  public function getOverdue(): Collection;

  public function getPending(): Collection;

  public function getByStatus(string $status): Collection;

  public function findByCode(string $code): ?Borrowing;

  public function getRecentForUser(int $userId, int $limit = 5): Collection;

  public function paginateByUser(int $userId, int $perPage = 10): LengthAwarePaginator;

  public function countActiveByUser(int $userId): int;

  public function getBetweenDates(string $startDate, string $endDate): Collection;
}
