<?php

namespace App\Exports;

use App\Services\DashboardService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueExport implements FromArray, WithHeadings, WithStyles
{
  public function array(): array
  {
    $data = app(DashboardService::class)->getMonthlyRevenue(12);
    $rows = [];

    foreach ($data as $item) {
      $rows[] = [
        $item['month'],
        'Rp ' . number_format($item['revenue'], 0, ',', '.'),
      ];
    }

    // Add total row
    $total = array_sum(array_column($data, 'revenue'));
    $rows[] = ['TOTAL', 'Rp ' . number_format($total, 0, ',', '.')];

    return $rows;
  }

  public function headings(): array
  {
    return [
      'Bulan',
      'Pendapatan',
    ];
  }

  public function styles(Worksheet $sheet): array
  {
    $lastRow = $sheet->getHighestRow();
    return [
      1 => ['font' => ['bold' => true]],
      $lastRow => ['font' => ['bold' => true]],
    ];
  }
}
