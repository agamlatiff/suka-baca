<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use App\Models\Payment;
use Filament\Widgets\Widget;

class LatestActivity extends Widget
{
  protected string $view = 'filament.widgets.latest-activity';

  protected static ?int $sort = 5;

  protected int | string | array $columnSpan = 1;

  public int $limit = 5;

  public function loadMore(): void
  {
    $this->limit += 5;
  }

  protected function getViewData(): array
  {
    $limit = $this->limit;

    $borrowings = Borrowing::with('user', 'bookCopy.book')
      ->latest('created_at')
      ->limit($limit)
      ->get();

    $payments = Payment::with('user')
      ->latest('created_at')
      ->limit($limit)
      ->get();

    $activities = $borrowings->map(function ($borrowing) {
      return [
        'type' => 'borrowing',
        'user' => $borrowing->user->name,
        'description' => "meminjam buku \"{$borrowing->bookCopy->book->title}\"",
        'time' => $borrowing->created_at->diffForHumans(),
        'date' => $borrowing->created_at,
        'status' => $borrowing->status,
      ];
    })->merge($payments->map(function ($payment) {
      return [
        'type' => 'payment',
        'user' => $payment->user->name,
        'description' => "melakukan pembayaran Rp " . number_format($payment->amount, 0, ',', '.'),
        'time' => $payment->created_at->diffForHumans(),
        'date' => $payment->created_at,
        'status' => $payment->status,
      ];
    }))
      ->sortByDesc('date')
      ->take($limit);

    return [
      'activities' => $activities,
    ];
  }
}
