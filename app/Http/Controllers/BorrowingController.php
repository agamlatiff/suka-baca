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
    $status = $request->query('status', 'all');
    $search = $request->query('search');

    $query = Auth::user()->borrowings()
      ->with(['bookCopy.book' => function ($q) {
        $q->select('id', 'title', 'author', 'image');
      }]);

    // Apply Search
    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('borrowing_code', 'like', "%{$search}%")
          ->orWhereHas('bookCopy.book', function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%");
          });
      });
    }

    // Apply Filter
    switch ($status) {
      case 'pending':
        $query->where('status', 'pending');
        break;
      case 'active':
        $query->where('status', 'active');
        break;
      case 'returned':
        $query->where('status', 'returned');
        break;
      case 'overdue':
        $query->where(function ($q) {
          $q->where('status', 'overdue')
            ->orWhere(function ($sub) {
              $sub->where('status', 'active')
                ->where('due_date', '<', now());
            });
        });
        break;
    }

    $borrowings = $query->latest()->paginate(10)->appends($request->query());

    // Counts for tabs
    $counts = [
      'all' => Auth::user()->borrowings()->count(),
      'pending' => Auth::user()->borrowings()->where('status', 'pending')->count(),
      'active' => Auth::user()->borrowings()->where('status', 'active')->count(),
      'returned' => Auth::user()->borrowings()->where('status', 'returned')->count(),
      'overdue' => Auth::user()->borrowings()->where(function ($q) {
        $q->where('status', 'overdue')
          ->orWhere(function ($sub) {
            $sub->where('status', 'active')
              ->where('due_date', '<', now());
          });
      })->count(),
    ];

    return view('borrowings.index', [
      'borrowings' => $borrowings,
      'counts' => $counts,
      'currentStatus' => $status,
    ]);
  }

  /**
   * Store a new borrowing.
   */
  public function store(BorrowBookRequest $request): RedirectResponse
  {
    $userId = Auth::id();

    // Full validation using validation service
    $validation = $this->validationService->canBorrow($userId, $request->book_id);

    if (!$validation['valid']) {
      $firstError = $validation['errors'][0] ?? ['message' => 'Tidak dapat meminjam.'];
      return back()->with('error', $firstError['message']);
    }

    // Get available copy from validation
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
