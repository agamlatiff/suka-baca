<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowBookRequest;
use App\Services\BorrowingService;
use App\Services\BorrowingValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BorrowingController extends Controller
{
  public function __construct(
    protected BorrowingService $borrowingService,
    protected BorrowingValidationService $validationService
  ) {}

  /**
   * Display user's borrowings.
   */
  public function index(Request $request): View
  {
    $userId = Auth::id();

    $filters = [
      'status' => $request->query('status', 'all'),
      'search' => $request->query('search'),
    ];

    $borrowings = $this->borrowingService->getUserBorrowingsFiltered($userId, $filters, 10);
    $borrowings->appends($request->query());

    $counts = $this->borrowingService->getBorrowingCounts($userId);

    return view('borrowings.index', [
      'borrowings' => $borrowings,
      'counts' => $counts,
      'currentStatus' => $filters['status'],
    ]);
  }

  /**
   * Store a new borrowing.
   */
  public function store(BorrowBookRequest $request): RedirectResponse
  {
    $userId = Auth::id();

    $validation = $this->validationService->canBorrow($userId, $request->book_id);

    if (!$validation['valid']) {
      $firstError = $validation['errors'][0] ?? ['message' => 'Tidak dapat meminjam.'];
      return back()->with('error', $firstError['message']);
    }

    $availability = $this->validationService->checkBookAvailability($request->book_id);

    if (!$availability['valid'] || !isset($availability['available_copy_id'])) {
      return back()->with('error', 'Maaf, tidak ada eksemplar yang tersedia.');
    }

    try {
      $borrowing = $this->borrowingService->createBorrowing(
        $userId,
        $availability['available_copy_id'],
        $request->book_id
      );

      return redirect()->route('borrowings.index')
        ->with('success', "Buku berhasil dipinjam! Kode peminjaman: {$borrowing->borrowing_code}");
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal memproses peminjaman. Silakan coba lagi.');
    }
  }

  /**
   * Check if user can borrow (API endpoint).
   */
  public function checkEligibility(Request $request)
  {
    $userId = Auth::id();
    $bookId = $request->input('book_id');

    $validation = $this->validationService->canBorrow($userId, $bookId);
    $summary = $this->validationService->getUserBorrowingSummary($userId);

    return response()->json([
      'can_borrow' => $validation['valid'],
      'errors' => $validation['errors'],
      'summary' => $summary,
    ]);
  }
}
