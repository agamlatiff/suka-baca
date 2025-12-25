<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueStats extends StatsOverviewWidget
{
  protected static ?int $sort = 2;

  protected static ?string $pollingInterval = '30s';

  protected function getStats(): array
  {
    $today = Carbon::today();
    $startOfWeek = Carbon::now()->startOfWeek();
    $startOfMonth = Carbon::now()->startOfMonth();

    // Calculate revenue from verified payments
    $revenueToday = Payment::where('status', 'verified')
      ->whereDate('verified_at', $today)
      ->sum('amount');

    $revenueWeek = Payment::where('status', 'verified')
      ->where('verified_at', '>=', $startOfWeek)
      ->sum('amount');

    $revenueMonth = Payment::where('status', 'verified')
      ->where('verified_at', '>=', $startOfMonth)
      ->sum('amount');

    // Pending verifications count
    $pendingPayments = Payment::where('status', 'pending')->count();

    // Pending borrowing requests
    $pendingBorrowings = Borrowing::where('status', 'pending')->count();

    $totalPending = $pendingPayments + $pendingBorrowings;

    return [
      Stat::make('Revenue Hari Ini', 'Rp ' . number_format($revenueToday, 0, ',', '.'))
        ->description('Pembayaran terverifikasi')
        ->icon('heroicon-o-currency-dollar')
        ->color('success'),

      Stat::make('Revenue Minggu Ini', 'Rp ' . number_format($revenueWeek, 0, ',', '.'))
        ->description('Sejak ' . $startOfWeek->format('d M'))
        ->icon('heroicon-o-chart-bar')
        ->color('info'),

      Stat::make('Revenue Bulan Ini', 'Rp ' . number_format($revenueMonth, 0, ',', '.'))
        ->description($startOfMonth->format('F Y'))
        ->icon('heroicon-o-banknotes')
        ->color('primary'),

      Stat::make('Menunggu Verifikasi', $totalPending)
        ->description($pendingPayments . ' bayar, ' . $pendingBorrowings . ' request')
        ->icon('heroicon-o-clock')
        ->color($totalPending > 0 ? 'warning' : 'success')
        ->url(route('filament.admin.resources.borrowings.index', ['tableFilters[status][value]' => 'pending'])),
    ];
  }
}
