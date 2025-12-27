<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\BookRepositoryInterface;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
  public function __construct(Book $model)
  {
    parent::__construct($model);
  }

  public function getPopular(int $limit = 10): Collection
  {
    return $this->model
      ->orderByDesc('times_borrowed')
      ->limit($limit)
      ->get();
  }

  public function getLatest(int $limit = 10): Collection
  {
    return $this->model
      ->latest()
      ->limit($limit)
      ->get();
  }

  public function getByCategory(int $categoryId): Collection
  {
    return $this->model
      ->where('category_id', $categoryId)
      ->get();
  }

  public function search(string $query): Collection
  {
    return $this->model
      ->where('title', 'like', "%{$query}%")
      ->orWhere('author', 'like', "%{$query}%")
      ->orWhere('isbn', 'like', "%{$query}%")
      ->get();
  }

  public function getAvailable(): Collection
  {
    return $this->model
      ->where('available_copies', '>', 0)
      ->get();
  }

  public function findBySlug(string $slug): ?Book
  {
    return $this->model->where('slug', $slug)->first();
  }

  public function paginateWithFilters(array $filters, int $perPage = 12): LengthAwarePaginator
  {
    $query = $this->model->query()->with('category');

    if (!empty($filters['search'])) {
      $search = $filters['search'];
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('author', 'like', "%{$search}%");
      });
    }

    if (!empty($filters['category_id'])) {
      $query->where('category_id', $filters['category_id']);
    }

    if (!empty($filters['available_only'])) {
      $query->where('available_copies', '>', 0);
    }

    $sortBy = $filters['sort_by'] ?? 'latest';
    match ($sortBy) {
      'popular' => $query->orderByDesc('times_borrowed'),
      'title_asc' => $query->orderBy('title'),
      'title_desc' => $query->orderByDesc('title'),
      'price_asc' => $query->orderBy('rental_fee'),
      'price_desc' => $query->orderByDesc('rental_fee'),
      'random' => $query->inRandomOrder(),
      default => $query->latest(),
    };

    return $query->paginate($perPage);
  }

  public function updateAvailability(int $bookId): void
  {
    $book = $this->findOrFail($bookId);
    $availableCount = $book->copies()->where('status', 'available')->count();
    $totalCount = $book->copies()->count();

    $book->update([
      'available_copies' => $availableCount,
      'total_copies' => $totalCount,
    ]);
  }
}
