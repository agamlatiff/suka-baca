<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BorrowingValidationService;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
  public function __construct(
    protected BorrowingValidationService $validationService
  ) {}

  /**
   * Show the borrowing wizard for a specific book.
   */
  public function create(Book $book)
  {
    $validation = $this->validationService->canBorrow(Auth::id(), $book->id);

    if (!$validation['valid']) {
      $error = $validation['errors'][0]['message'] ?? 'Tidak dapat meminjam buku ini.';
      return redirect()->route('catalog.show', $book)->with('error', $error);
    }

    return view('borrow.create', compact('book'));
  }
}
