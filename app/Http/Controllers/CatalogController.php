<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
  public function __construct(
    protected BookService $bookService
  ) {}

  /**
   * Display catalog listing with search and filters.
   */
  public function index(Request $request): View
  {
    $filters = [
      'search' => $request->get('search'),
      'category_id' => $request->get('category'),
      'available_only' => $request->get('show_all') !== '1',
      'sort_by' => $request->get('sort', 'latest'),
    ];

    $books = $this->bookService->getCatalog($filters, 12);
    $categories = $this->bookService->getCategories();

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
  public function show(string $slug): View
  {
    $book = $this->bookService->getBookBySlug($slug);

    if (!$book) {
      abort(404);
    }

    $book->load(['category', 'copies' => function ($query) {
      $query->where('status', 'available')->limit(5);
    }]);

    // Get related books from same category
    $relatedBooks = Book::where('category_id', $book->category_id)
      ->where('id', '!=', $book->id)
      ->where('available_copies', '>', 0)
      ->inRandomOrder()
      ->limit(4)
      ->get();

    return view('catalog.show', [
      'book' => $book,
      'relatedBooks' => $relatedBooks,
    ]);
  }
}
