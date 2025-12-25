<?php

namespace App\Services;

use App\Contracts\Repositories\BookRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookService
{
  public function __construct(
    protected BookRepositoryInterface $bookRepository,
    protected CategoryRepositoryInterface $categoryRepository
  ) {}

  public function getAllBooks(): Collection
  {
    return $this->bookRepository->all();
  }

  public function getBookById(int $id): ?Book
  {
    return $this->bookRepository->find($id);
  }

  public function getBookBySlug(string $slug): ?Book
  {
    return $this->bookRepository->findBySlug($slug);
  }

  public function getPopularBooks(int $limit = 10): Collection
  {
    return $this->bookRepository->getPopular($limit);
  }

  public function getLatestBooks(int $limit = 10): Collection
  {
    return $this->bookRepository->getLatest($limit);
  }

  public function getBooksByCategory(int $categoryId): Collection
  {
    return $this->bookRepository->getByCategory($categoryId);
  }

  public function searchBooks(string $query): Collection
  {
    return $this->bookRepository->search($query);
  }

  public function getAvailableBooks(): Collection
  {
    return $this->bookRepository->getAvailable();
  }

  public function getCatalog(array $filters, int $perPage = 12): LengthAwarePaginator
  {
    return $this->bookRepository->paginateWithFilters($filters, $perPage);
  }

  public function createBook(array $data): Book
  {
    return $this->bookRepository->create($data);
  }

  public function updateBook(int $id, array $data): bool
  {
    return $this->bookRepository->update($id, $data);
  }

  public function deleteBook(int $id): bool
  {
    return $this->bookRepository->delete($id);
  }

  public function updateBookAvailability(int $bookId): void
  {
    $this->bookRepository->updateAvailability($bookId);
  }

  public function isBookAvailable(int $bookId): bool
  {
    $book = $this->bookRepository->find($bookId);
    return $book && $book->available_copies > 0;
  }

  public function getCategories(): Collection
  {
    return $this->categoryRepository->getWithBookCount();
  }

  public function getPopularCategories(int $limit = 6): Collection
  {
    return $this->categoryRepository->getPopular($limit);
  }
}
