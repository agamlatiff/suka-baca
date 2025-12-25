<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
  /**
   * Display the homepage.
   */
  public function index(): View
  {
    // Get categories with book count
    $categories = Category::withCount('books')
      ->orderBy('books_count', 'desc')
      ->take(6)
      ->get();

    // Get latest books
    $latestBooks = Book::with('category')
      ->latest()
      ->take(4)
      ->get();

    // Get popular books (most borrowed)
    $popularBooks = Book::withCount('borrowings')
      ->orderBy('borrowings_count', 'desc')
      ->take(4)
      ->get()
      ->map(function ($book) {
        $book->times_borrowed = $book->borrowings_count;
        return $book;
      });

    // Get settings from database
    $settings = [];
    $settingRecords = Setting::all()->pluck('value', 'key')->toArray();
    $settings = [
      'library_name' => $settingRecords['library_name'] ?? 'Sukabaca',
      'library_address' => $settingRecords['library_address'] ?? 'Jl. Pustaka No. 88, Jakarta',
      'library_email' => $settingRecords['library_email'] ?? 'halo@sukabaca.id',
      'library_phone' => $settingRecords['library_phone'] ?? '+62 812 3456 7890',
    ];

    return view('home', compact(
      'categories',
      'latestBooks',
      'popularBooks',
      'settings'
    ));
  }
}
