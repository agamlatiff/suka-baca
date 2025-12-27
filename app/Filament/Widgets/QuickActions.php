<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use App\Models\Payment;
use Filament\Widgets\Widget;

class QuickActions extends Widget
{
  protected string $view = 'filament.widgets.quick-actions';

  protected static ?int $sort = 2;

  protected int | string | array $columnSpan = 'full';

  public function getPendingBorrowingsCount(): int
  {
    return Borrowing::where('status', 'pending')->count();
  }

  public function getPendingPaymentsCount(): int
  {
    return Payment::where('status', 'pending')->count();
  }

  public function getOverdueCount(): int
  {
    return Borrowing::whereNull('returned_at')
      ->where('due_date', '<', now())
      ->count();
  }
}
