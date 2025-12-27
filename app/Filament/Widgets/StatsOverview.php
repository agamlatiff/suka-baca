<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsOverview extends StatsOverviewWidget
{
  protected static ?int $sort = 1;
  protected ?string $pollingInterval = '30s';

  protected function getStats(): array
  {
    // Cache all stats for 5 minutes to reduce DB load
    $cached = Cache::remember('admin_stats_overview', 300, function () {
      // 1. Revenue Stats (Last 30 Days)
      $revenueCurrent = Payment::where('status', 'verified')
        ->where('verified_at', '>=', now()->subDays(30))
        ->sum('amount');

      $revenuePrevious = Payment::where('status', 'verified')
        ->whereBetween('verified_at', [
          now()->subDays(60),
          now()->subDays(30)
        ])
        ->sum('amount');

      $revenueChange = $this->calculateChange($revenueCurrent, $revenuePrevious);
      $revenueChart = $this->getChartData(Payment::class, 'verified_at', 'amount', 30, 'sum');

      // 2. New Borrowings (Last 30 Days)
      $borrowingCurrent = Borrowing::where('borrowed_at', '>=', now()->subDays(30))->count();
      $borrowingPrevious = Borrowing::whereBetween('borrowed_at', [
        now()->subDays(60),
        now()->subDays(30)
      ])->count();

      $borrowingChange = $this->calculateChange($borrowingCurrent, $borrowingPrevious);
      $borrowingChart = $this->getChartData(Borrowing::class, 'borrowed_at', '*', 30, 'count');

      // 3. Active Borrowings & Overdue
      $activeBorrowings = Borrowing::whereIn('status', ['active', 'overdue'])->count();
      $overdueCount = Borrowing::where('status', 'overdue')->count();
      $overduePercentage = $activeBorrowings > 0 ? round(($overdueCount / $activeBorrowings) * 100) : 0;

      // 4. New Members (Last 30 Days)
      $newMembersCurrent = User::where('role', 'user')
        ->where('created_at', '>=', now()->subDays(30))
        ->count();

      $newMembersPrevious = User::where('role', 'user')
        ->whereBetween('created_at', [
          now()->subDays(60),
          now()->subDays(30)
        ])->count();

      $memberChange = $this->calculateChange($newMembersCurrent, $newMembersPrevious);

      return [
        'revenueCurrent' => $revenueCurrent,
        'revenueChange' => $revenueChange,
        'revenueChart' => $revenueChart,
        'borrowingCurrent' => $borrowingCurrent,
        'borrowingChange' => $borrowingChange,
        'borrowingChart' => $borrowingChart,
        'activeBorrowings' => $activeBorrowings,
        'overdueCount' => $overdueCount,
        'overduePercentage' => $overduePercentage,
        'newMembersCurrent' => $newMembersCurrent,
        'memberChange' => $memberChange,
      ];
    });

    return [
      Stat::make('Pendapatan (30 Hari)', 'Rp ' . number_format($cached['revenueCurrent'], 0, ',', '.'))
        ->description($cached['revenueChange']['text'])
        ->descriptionIcon($cached['revenueChange']['icon'])
        ->color($cached['revenueChange']['color'])
        ->chart($cached['revenueChart']),

      Stat::make('Peminjaman Baru', $cached['borrowingCurrent'])
        ->description($cached['borrowingChange']['text'])
        ->descriptionIcon($cached['borrowingChange']['icon'])
        ->color($cached['borrowingChange']['color'])
        ->chart($cached['borrowingChart']),

      Stat::make('Peminjaman Aktif', $cached['activeBorrowings'])
        ->description("{$cached['overdueCount']} Terlambat ({$cached['overduePercentage']}%)")
        ->descriptionIcon('heroicon-m-exclamation-triangle')
        ->color($cached['overdueCount'] > 0 ? 'danger' : 'success')
        ->chart([$cached['activeBorrowings'], $cached['activeBorrowings'], $cached['activeBorrowings']]),

      Stat::make('Anggota Baru', $cached['newMembersCurrent'])
        ->description($cached['memberChange']['text'])
        ->descriptionIcon($cached['memberChange']['icon'])
        ->color($cached['memberChange']['color']),
    ];
  }

  private function calculateChange($current, $previous): array
  {
    if ($previous == 0) {
      if ($current > 0) return [
        'text' => 'Naik 100%',
        'icon' => 'heroicon-m-arrow-trending-up',
        'color' => 'success'
      ];
      return [
        'text' => 'Tidak ada perubahan',
        'icon' => 'heroicon-m-minus',
        'color' => 'gray'
      ];
    }

    $change = (($current - $previous) / $previous) * 100;
    $rounded = round($change, 1);

    if ($change > 0) {
      return [
        'text' => "Naik {$rounded}%",
        'icon' => 'heroicon-m-arrow-trending-up',
        'color' => 'success'
      ];
    } elseif ($change < 0) {
      return [
        'text' => "Turun " . abs($rounded) . "%",
        'icon' => 'heroicon-m-arrow-trending-down',
        'color' => 'danger'
      ];
    }

    return [
      'text' => 'Tidak ada perubahan',
      'icon' => 'heroicon-m-minus',
      'color' => 'gray'
    ];
  }

  private function getChartData($model, $dateColumn, $valueColumn, $days, $aggregate): array
  {
    $data = $model::query()
      ->where($dateColumn, '>=', now()->subDays($days))
      ->selectRaw("DATE($dateColumn) as date, " . ($aggregate === 'sum' ? "SUM($valueColumn)" : "COUNT(*)") . " as value")
      ->groupBy('date')
      ->orderBy('date')
      ->pluck('value', 'date')
      ->toArray();

    $chart = [];
    for ($i = $days - 1; $i >= 0; $i--) {
      $date = now()->subDays($i)->format('Y-m-d');
      $chart[] = $data[$date] ?? 0;
    }

    return $chart;
  }
}
