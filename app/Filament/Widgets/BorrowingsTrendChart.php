<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class BorrowingsTrendChart extends ChartWidget
{
  protected static ?int $sort = 2;

  protected ?string $heading = 'Tren Peminjaman (30 Hari)';

  protected ?string $maxHeight = '250px';

  protected int | string | array $columnSpan = 1;

  protected function getData(): array
  {
    $data = Borrowing::query()
      ->where('borrowed_at', '>=', now()->subDays(29))
      ->selectRaw('DATE(borrowed_at) as date, count(*) as count')
      ->groupBy('date')
      ->pluck('count', 'date')
      ->toArray();

    $days = [];
    $counts = [];

    for ($i = 29; $i >= 0; $i--) {
      $dateObj = Carbon::now()->subDays($i);
      $dateStr = $dateObj->format('Y-m-d');
      $days[] = $dateObj->format('d M');
      $counts[] = $data[$dateStr] ?? 0;
    }

    return [
      'datasets' => [
        [
          'label' => 'Peminjaman',
          'data' => $counts,
          'borderColor' => 'rgb(59, 130, 246)',
          'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
          'fill' => true,
          'tension' => 0.4,
        ],
      ],
      'labels' => $days,
    ];
  }

  protected function getType(): string
  {
    return 'line';
  }

  protected function getOptions(): array
  {
    return [
      'plugins' => [
        'legend' => [
          'display' => false,
        ],
      ],
      'scales' => [
        'y' => [
          'beginAtZero' => true,
          'ticks' => [
            'stepSize' => 1,
          ],
        ],
        'x' => [
          'ticks' => [
            'maxTicksLimit' => 10,
          ],
        ],
      ],
    ];
  }
}
