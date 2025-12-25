<?php

namespace App\Contracts\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface extends BaseRepositoryInterface
{
  public function getPopular(int $limit = 10): Collection;

  public function getLatest(int $limit = 10): Collection;

  public function getByCategory(int $categoryId): Collection;

  public function search(string $query): Collection;

  public function getAvailable(): Collection;

  public function findBySlug(string $slug): ?Book;

  public function paginateWithFilters(array $filters, int $perPage = 12): LengthAwarePaginator;

  public function updateAvailability(int $bookId): void;
}
