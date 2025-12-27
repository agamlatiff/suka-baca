<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RevenueTrendChart extends ChartWidget
{
  protected static ?int $sort = 3;

  protected ?string $heading = 'Tren Pendapatan (30 Hari)';

  protected ?string $maxHeight = '250px';

  protected int | string | array $columnSpan = 1;

  protected function getData(): array
  {
    $data = Payment::query()
      ->where('status', 'verified')
      ->where('verified_at', '>=', now()->subDays(29))
      ->selectRaw('DATE(verified_at) as date, SUM(amount) as revenue')
      ->groupBy('date')
      ->pluck('revenue', 'date')
      ->toArray();

    $days = [];
    $revenues = [];

    for ($i = 29; $i >= 0; $i--) {
      $dateObj = Carbon::now()->subDays($i);
      $dateStr = $dateObj->format('Y-m-d');
      $days[] = $dateObj->format('d M');
      $revenues[] = $data[$dateStr] ?? 0;
    }

    return [
      'datasets' => [
        [
          'label' => 'Pendapatan (Rp)',
          'data' => $revenues,
          'borderColor' => 'rgb(34, 197, 94)',
          'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
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
            'callback' => 'function(value) { return "Rp " + value.toLocaleString(); }',
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
