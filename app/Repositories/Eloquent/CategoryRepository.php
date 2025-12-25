<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
  public function __construct(Category $model)
  {
    parent::__construct($model);
  }

  public function getWithBookCount(): Collection
  {
    return $this->model
      ->withCount('books')
      ->orderBy('name')
      ->get();
  }

  public function getPopular(int $limit = 6): Collection
  {
    return $this->model
      ->withCount('books')
      ->orderByDesc('books_count')
      ->limit($limit)
      ->get();
  }

  public function findBySlug(string $slug): ?object
  {
    return $this->model->where('slug', $slug)->first();
  }
}
