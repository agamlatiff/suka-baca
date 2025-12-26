<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BorrowingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
  public function __construct(
    private BorrowingService $borrowingService
  ) {}

  /**
   * Show the borrowing wizard for a specific book.
   */
  public function create(Book $book)
  {
    // Check if book is available
    if (!$book->is_available()) {
      return redirect()->route('catalog.show', $book)
        ->with('error', 'Maaf, buku ini sedang tidak tersedia.');
    }

    // Check if user has reached max borrowing limit
    $user = Auth::user();
    $activeBorrowings = $user->borrowings()->whereIn('status', ['active', 'pending'])->count();
    $maxBooks = (int) \App\Models\Setting::get('max_books_per_user', 2);

    if ($activeBorrowings >= $maxBooks) {
      return redirect()->route('catalog.show', $book)
        ->with('error', "Anda sudah mencapai batas maksimal peminjaman ($maxBooks buku).");
    }

    // Check for outstanding fees
    $outstandingFees = $user->borrowings()
      ->where('is_paid', false)
      ->sum('total_fee');

    if ($outstandingFees > 0) {
      return redirect()->route('catalog.show', $book)
        ->with('error', 'Anda memiliki tagihan yang belum dibayar. Mohon selesaikan terlebih dahulu.');
    }

    return view('borrow.create', compact('book'));
  }
}
