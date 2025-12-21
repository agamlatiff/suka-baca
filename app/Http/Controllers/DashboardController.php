<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
  /**
   * Display the user dashboard.
   */
  public function index(): View
  {
    /** @var User $user */
    $user = Auth::user();

    // Get active borrowings
    $activeBorrowings = Borrowing::with(['book', 'bookCopy'])
      ->where('user_id', $user->id)
      ->whereNull('returned_at')
      ->orderBy('due_date', 'asc')
      ->get();

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
      'outstanding_fees' => Borrowing::where('user_id', $user->id)
        ->where('is_paid', false)
        ->sum('total_fee'),
    ];

    // Get recent history
    $recentHistory = Borrowing::with(['book'])
      ->where('user_id', $user->id)
      ->whereNotNull('returned_at')
      ->orderBy('returned_at', 'desc')
      ->limit(5)
      ->get();

    return view('dashboard', [
      'activeBorrowings' => $activeBorrowings,
      'stats' => $stats,
      'recentHistory' => $recentHistory,
    ]);
  }
}
