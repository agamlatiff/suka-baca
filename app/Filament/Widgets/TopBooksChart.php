<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Widgets\ChartWidget;

class TopBooksChart extends ChartWidget
{
  protected static ?int $sort = 3;

  protected ?string $heading = 'Buku Terpopuler';

  protected ?string $maxHeight = '300px';

  protected function getData(): array
  {
    $topBooks = Book::orderBy('times_borrowed', 'desc')
      ->limit(5)
      ->get();

    return [
      'datasets' => [
        [
          'label' => 'Kali Dipinjam',
          'data' => $topBooks->pluck('times_borrowed')->toArray(),
          'backgroundColor' => [
            'rgba(99, 102, 241, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(14, 165, 233, 0.8)',
            'rgba(6, 182, 212, 0.8)',
            'rgba(20, 184, 166, 0.8)',
          ],
          'borderColor' => [
            'rgb(99, 102, 241)',
            'rgb(59, 130, 246)',
            'rgb(14, 165, 233)',
            'rgb(6, 182, 212)',
            'rgb(20, 184, 166)',
          ],
          'borderWidth' => 1,
        ],
      ],
      'labels' => $topBooks->pluck('title')->map(
        fn($title) =>
        strlen($title) > 20 ? substr($title, 0, 20) . '...' : $title
      )->toArray(),
    ];
  }

  protected function getType(): string
  {
    return 'bar';
  }

  protected function getOptions(): array
  {
    return [
      'indexAxis' => 'y',
      'plugins' => [
        'legend' => [
          'display' => false,
        ],
      ],
      'scales' => [
        'x' => [
          'beginAtZero' => true,
          'ticks' => [
            'stepSize' => 1,
          ],
        ],
      ],
    ];
  }
}
