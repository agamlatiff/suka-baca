<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Widgets\ChartWidget;

class CategoryDistributionChart extends ChartWidget
{
  protected static ?int $sort = 4;

  protected ?string $heading = 'Distribusi Kategori Buku';

  protected ?string $maxHeight = '300px';

  protected int | string | array $columnSpan = 1;

  protected function getData(): array
  {
    $data = Book::selectRaw('categories.name, count(*) as count')
      ->join('categories', 'books.category_id', '=', 'categories.id')
      ->groupBy('categories.name')
      ->pluck('count', 'categories.name')
      ->toArray();

    return [
      'datasets' => [
        [
          'label' => 'Buku',
          'data' => array_values($data),
          'backgroundColor' => [
            'rgb(59, 130, 246)', // Blue
            'rgb(239, 68, 68)',  // Red
            'rgb(16, 185, 129)', // Green
            'rgb(245, 158, 11)', // Amber
            'rgb(99, 102, 241)', // Indigo
            'rgb(139, 92, 246)', // Violet
            'rgb(236, 72, 153)', // Pink
            'rgb(20, 184, 166)', // Teal
            'rgb(249, 115, 22)', // Orange
            'rgb(100, 116, 139)', // Slate
          ],
          'hoverOffset' => 4,
        ],
      ],
      'labels' => array_keys($data),
    ];
  }

  protected function getType(): string
  {
    return 'doughnut';
  }
}
