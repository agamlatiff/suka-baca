<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\BorrowingRepositoryInterface;
use App\Models\Borrowing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BorrowingRepository extends BaseRepository implements BorrowingRepositoryInterface
{
  public function __construct(Borrowing $model)
  {
    parent::__construct($model);
  }

  public function getByUser(int $userId): Collection
  {
    return $this->model
      ->where('user_id', $userId)
      ->with(['book', 'bookCopy'])
      ->latest('borrowed_at')
      ->get();
  }

  public function getActive(): Collection
  {
    return $this->model
      ->where('status', 'active')
      ->with(['user', 'book', 'bookCopy'])
      ->get();
  }

  public function getOverdue(): Collection
  {
    return $this->model
      ->where('status', 'overdue')
      ->orWhere(function ($query) {
        $query->where('status', 'active')
          ->where('due_date', '<', now());
      })
      ->with(['user', 'book', 'bookCopy'])
      ->get();
  }

  public function getPending(): Collection
  {
    return $this->model
      ->where('status', 'pending')
      ->with(['user', 'book', 'bookCopy'])
      ->latest()
      ->get();
  }

  public function getByStatus(string $status): Collection
  {
    return $this->model
      ->where('status', $status)
      ->with(['user', 'book', 'bookCopy'])
      ->get();
  }

  public function findByCode(string $code): ?Borrowing
  {
    return $this->model->where('borrowing_code', $code)->first();
  }

  public function getRecentForUser(int $userId, int $limit = 5): Collection
  {
    return $this->model
      ->where('user_id', $userId)
      ->with(['book', 'bookCopy'])
      ->latest('borrowed_at')
      ->limit($limit)
      ->get();
  }

  public function paginateByUser(int $userId, int $perPage = 10): LengthAwarePaginator
  {
    return $this->model
      ->where('user_id', $userId)
      ->with(['book', 'bookCopy'])
      ->latest('borrowed_at')
      ->paginate($perPage);
  }

  public function countActiveByUser(int $userId): int
  {
    return $this->model
      ->where('user_id', $userId)
      ->whereIn('status', ['active', 'pending'])
      ->count();
  }

  public function getBetweenDates(string $startDate, string $endDate): Collection
  {
    return $this->model
      ->whereBetween('borrowed_at', [$startDate, $endDate])
      ->with(['user', 'book', 'bookCopy'])
      ->latest('borrowed_at')
      ->get();
  }
}
