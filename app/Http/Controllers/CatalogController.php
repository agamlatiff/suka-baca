<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
  /**
   * Display catalog listing with search and filters.
   */
  public function index(Request $request): View
  {
    $query = Book::with('category')
      ->where('available_copies', '>', 0);

    // Search by title or author
    if ($search = $request->get('search')) {
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('author', 'like', "%{$search}%");
      });
    }

    // Filter by category
    if ($categoryId = $request->get('category')) {
      $query->where('category_id', $categoryId);
    }

    // Show all books (including out of stock) if requested
    if ($request->get('show_all') === '1') {
      $query = Book::with('category');

      if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
          $q->where('title', 'like', "%{$search}%")
            ->orWhere('author', 'like', "%{$search}%");
        });
      }

      if ($categoryId = $request->get('category')) {
        $query->where('category_id', $categoryId);
      }
    }

    $books = $query->orderBy('title')->paginate(12)->withQueryString();
    $categories = Category::orderBy('name')->get();

    return view('catalog.index', [
      'books' => $books,
      'categories' => $categories,
      'filters' => [
        'search' => $request->get('search'),
        'category' => $request->get('category'),
        'show_all' => $request->get('show_all'),
      ],
    ]);
  }

  /**
   * Display book detail page.
   */
  public function show(Book $book): View
  {
    $book->load(['category', 'copies' => function ($query) {
      $query->where('status', 'available')->limit(5);
    }]);

    return view('catalog.show', [
      'book' => $book,
    ]);
  }
}
