<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
  public function all(): Collection;

  public function find(int $id): ?object;

  public function findOrFail(int $id): object;

  public function create(array $data): object;

  public function update(int $id, array $data): bool;

  public function delete(int $id): bool;

  public function paginate(int $perPage = 15): LengthAwarePaginator;
}
