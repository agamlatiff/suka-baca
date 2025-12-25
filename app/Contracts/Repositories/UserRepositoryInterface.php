<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
  public function getMembers(): Collection;

  public function getAdmins(): Collection;

  public function getActive(): Collection;

  public function getSuspended(): Collection;

  public function findByEmail(string $email): ?object;

  public function getTopBorrowers(int $limit = 10, ?string $startDate = null, ?string $endDate = null): Collection;

  public function suspend(int $userId): bool;

  public function activate(int $userId): bool;
}
