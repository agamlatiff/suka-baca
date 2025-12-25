<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
  public function getWithBookCount(): Collection;

  public function getPopular(int $limit = 6): Collection;

  public function findBySlug(string $slug): ?object;
}
