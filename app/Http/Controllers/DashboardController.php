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
    $userId = Auth::id();

    // Get active borrowings
    $activeBorrowings = $this->borrowingService->getUserBorrowings($userId)
      ->whereNull('returned_at')
      ->sortBy('due_date');

    // Calculate stats
    $now = Carbon::now();
    $stats = [
      'active_count' => $activeBorrowings->count(),
      'due_soon_count' => $activeBorrowings->filter(function ($b) use ($now) {
        $daysLeft = $now->diffInDays(Carbon::parse($b->due_date), false);
        return $daysLeft >= 0 && $daysLeft <= 3;
      })->count(),
      'overdue_count' => $activeBorrowings->filter(function ($b) use ($now) {
        return Carbon::parse($b->due_date)->lt($now);
      })->count(),
      'outstanding_fees' => $this->borrowingService->getUserBorrowings($userId)
        ->where('is_paid', false)
        ->sum('total_fee'),
    ];

    // Get recent history
    $recentHistory = $this->borrowingService->getUserBorrowings($userId)
      ->whereNotNull('returned_at')
      ->sortByDesc('returned_at')
      ->take(5);

    return view('dashboard', [
      'activeBorrowings' => $activeBorrowings,
      'stats' => $stats,
      'recentHistory' => $recentHistory,
    ]);
  }
}
