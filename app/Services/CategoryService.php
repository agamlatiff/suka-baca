<?php

namespace App\Services;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
  public function __construct(
    protected CategoryRepositoryInterface $categoryRepository
  ) {}

  public function getAllCategories(): Collection
  {
    return $this->categoryRepository->all();
  }

  public function getCategoriesWithBookCount(): Collection
  {
    return $this->categoryRepository->getWithBookCount();
  }

  public function getPopularCategories(int $limit = 6): Collection
  {
    return $this->categoryRepository->getPopular($limit);
  }

  public function getCategoryById(int $id): ?object
  {
    return $this->categoryRepository->find($id);
  }

  public function getCategoryBySlug(string $slug): ?object
  {
    return $this->categoryRepository->findBySlug($slug);
  }

  public function createCategory(array $data): object
  {
    return $this->categoryRepository->create($data);
  }

  public function updateCategory(int $id, array $data): bool
  {
    return $this->categoryRepository->update($id, $data);
  }

  public function deleteCategory(int $id): bool
  {
    return $this->categoryRepository->delete($id);
  }
}
