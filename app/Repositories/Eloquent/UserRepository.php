<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
  public function __construct(User $model)
  {
    parent::__construct($model);
  }

  public function getMembers(): Collection
  {
    return $this->model
      ->where('role', 'user')
      ->orderBy('name')
      ->get();
  }

  public function getAdmins(): Collection
  {
    return $this->model
      ->where('role', 'admin')
      ->orderBy('name')
      ->get();
  }

  public function getActive(): Collection
  {
    return $this->model
      ->where('status', 'active')
      ->orderBy('name')
      ->get();
  }

  public function getSuspended(): Collection
  {
    return $this->model
      ->where('status', 'suspended')
      ->orderBy('name')
      ->get();
  }

  public function findByEmail(string $email): ?object
  {
    return $this->model->where('email', $email)->first();
  }

  public function getTopBorrowers(int $limit = 10, ?string $startDate = null, ?string $endDate = null): Collection
  {
    $query = $this->model
      ->where('role', 'user')
      ->withCount(['borrowings' => function ($q) use ($startDate, $endDate) {
        if ($startDate && $endDate) {
          $q->whereBetween('borrowed_at', [$startDate, $endDate]);
        }
      }])
      ->orderByDesc('borrowings_count')
      ->limit($limit);

    return $query->get();
  }

  public function suspend(int $userId): bool
  {
    return $this->update($userId, ['status' => 'suspended']);
  }

  public function activate(int $userId): bool
  {
    return $this->update($userId, ['status' => 'active']);
  }
}
