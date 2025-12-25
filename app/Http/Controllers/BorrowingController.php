<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowBookRequest;
use App\Models\BookCopy;
use App\Services\BorrowingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BorrowingController extends Controller
{
  public function __construct(
    protected BorrowingService $borrowingService
  ) {}

  /**
   * Display user's borrowings.
   */
  public function index(Request $request): View
  {
    $userId = Auth::id();

    $borrowings = $this->borrowingService->getUserBorrowingsPaginated($userId, 10);

    // Separate active and returned
    $activeBorrowings = $this->borrowingService->getUserBorrowings($userId)
      ->whereNull('returned_at');

    $history = $borrowings;

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
    $userId = Auth::id();

    // Check if user can borrow more books
    if (!$this->borrowingService->canUserBorrow($userId)) {
      return back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman.');
    }

    // Find an available copy
    $availableCopy = BookCopy::where('book_id', $request->book_id)
      ->where('status', 'available')
      ->first();

    if (!$availableCopy) {
      return back()->with('error', 'Maaf, tidak ada eksemplar yang tersedia.');
    }

    try {
      $borrowing = $this->borrowingService->createBorrowing(
        $userId,
        $availableCopy->id,
        $request->book_id
      );

      return redirect()->route('borrowings.index')
        ->with('success', "Buku berhasil dipinjam! Kode peminjaman: {$borrowing->borrowing_code}");
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal memproses peminjaman. Silakan coba lagi.');
    }
  }
}
