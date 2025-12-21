<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowBookRequest;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
  /**
   * Display user's borrowings.
   */
  public function index(Request $request): View
  {
    $user = auth()->user();

    // Get active borrowings
    $activeBorrowings = Borrowing::with(['book', 'bookCopy'])
      ->where('user_id', $user->id)
      ->whereNull('returned_at')
      ->orderBy('due_date', 'asc')
      ->get();

    // Get borrowing history
    $history = Borrowing::with(['book', 'bookCopy'])
      ->where('user_id', $user->id)
      ->whereNotNull('returned_at')
      ->orderBy('returned_at', 'desc')
      ->paginate(10);

    return view('borrowings.index', [
      'activeBorrowings' => $activeBorrowings,
      'history' => $history,
    ]);
  }

  /**
   * Store a new borrowing.
   */
  public function store(BorrowBookRequest $request): RedirectResponse
  {
    $user = auth()->user();
    $book = Book::findOrFail($request->book_id);
    $duration = (int) $request->duration;

    // Find an available copy
    $availableCopy = BookCopy::where('book_id', $book->id)
      ->where('status', 'available')
      ->first();

    if (!$availableCopy) {
      return back()->with('error', 'Maaf, tidak ada eksemplar yang tersedia.');
    }

    // Generate borrowing code
    $borrowingCode = $this->generateBorrowingCode();

    // Calculate dates
    $borrowedAt = Carbon::now();
    $dueDate = $borrowedAt->copy()->addDays($duration);

    // Create borrowing record
    $borrowing = Borrowing::create([
      'code' => $borrowingCode,
      'user_id' => $user->id,
      'book_id' => $book->id,
      'book_copy_id' => $availableCopy->id,
      'borrowed_at' => $borrowedAt,
      'due_date' => $dueDate,
      'rental_fee' => $book->rental_fee,
      'late_fee' => 0,
      'total_fee' => $book->rental_fee,
      'is_paid' => false,
    ]);

    // Update copy status
    $availableCopy->update(['status' => 'borrowed']);

    // Update book available copies
    $book->decrement('available_copies');

    return redirect()->route('borrowings.index')
      ->with('success', "Buku \"{$book->title}\" berhasil dipinjam! Kode peminjaman: {$borrowingCode}");
  }

  /**
   * Generate a unique borrowing code.
   * Format: BRW-YYYYMMDD-XXX
   */
  private function generateBorrowingCode(): string
  {
    $date = Carbon::now()->format('Ymd');
    $prefix = "BRW-{$date}-";

    // Get the last borrowing code for today
    $lastBorrowing = Borrowing::where('code', 'like', $prefix . '%')
      ->orderBy('code', 'desc')
      ->first();

    if ($lastBorrowing) {
      $lastNumber = (int) substr($lastBorrowing->code, -3);
      $newNumber = $lastNumber + 1;
    } else {
      $newNumber = 1;
    }

    return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
  }
}
