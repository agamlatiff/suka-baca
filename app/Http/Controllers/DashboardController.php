<?php

namespace App\Http\Controllers;

use App\Services\BorrowingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
  public function __construct(
    protected BorrowingService $borrowingService
  ) {}

  /**
   * Display the user dashboard.
   */
  public function index(): View
  {
    $user = Auth::user();

    // Get active borrowings (including overdue ones that are still active/not returned)
    $activeBorrowings = $user->borrowings()
      ->with(['bookCopy.book' => function ($query) {
        $query->select('id', 'title', 'author', 'image', 'slug');
      }])
      ->whereIn('status', ['active', 'overdue'])
      ->orderBy('due_date', 'asc')
      ->get();

    // Pending borrowings (requests)
    $pendingBorrowings = $user->borrowings()
      ->with(['bookCopy.book'])
      ->where('status', 'pending')
      ->latest()
      ->get();

    // Overdue borrowings for alert
    $overdueBorrowings = $activeBorrowings->filter(function ($borrowing) {
      return $borrowing->is_overdue || ($borrowing->status === 'active' && $borrowing->due_date < now());
    });

    // Unpaid bills
    $unpaidBorrowings = $user->borrowings()
      ->where('is_paid', false)
      ->where('total_fee', '>', 0)
      ->get();

    $stats = [
      'active_count' => $activeBorrowings->count(),
      'total_borrowed' => $user->borrowings()->count(),
      'wishlist_count' => $user->wishlists()->count(),
      'total_outstanding_fees' => $unpaidBorrowings->sum('total_fee'),
    ];

    return view('dashboard', [
      'user' => $user,
      'activeBorrowings' => $activeBorrowings,
      'pendingBorrowings' => $pendingBorrowings,
      'overdueBorrowings' => $overdueBorrowings,
      'unpaidBorrowings' => $unpaidBorrowings,
      'stats' => $stats,
    ]);
  }
}
