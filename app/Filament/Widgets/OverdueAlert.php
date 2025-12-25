<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class OverdueAlert extends Widget
{
  protected string $view = 'filament.widgets.overdue-alert';

  protected static ?int $sort = 0;

  protected int|string|array $columnSpan = 'full';

  public int $overdueCount = 0;
  public float $totalOverdueFees = 0;

  public function mount(): void
  {
    $this->loadOverdueData();
  }

  public function loadOverdueData(): void
  {
    $overdueQuery = Borrowing::whereNull('returned_at')
      ->where('due_date', '<', Carbon::today())
      ->where('status', '!=', 'returned');

    $this->overdueCount = $overdueQuery->count();

    // Calculate potential late fees
    $overdueBorrowings = $overdueQuery->get();
    $this->totalOverdueFees = $overdueBorrowings->sum(function ($borrowing) {
      $daysLate = Carbon::today()->diffInDays($borrowing->due_date);
      // 10% per day of rental fee
      return $daysLate * ($borrowing->rental_fee * 0.1);
    });
  }

  public static function canView(): bool
  {
    $overdueCount = Borrowing::whereNull('returned_at')
      ->where('due_date', '<', Carbon::today())
      ->where('status', '!=', 'returned')
      ->count();

    return $overdueCount > 0;
  }
}
