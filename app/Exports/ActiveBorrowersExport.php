<?php

namespace App\Exports;

use App\Services\UserService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActiveBorrowersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
  public function __construct(
    protected ?string $startDate = null,
    protected ?string $endDate = null
  ) {}

  public function collection()
  {
    return app(UserService::class)->getTopBorrowers(50, $this->startDate, $this->endDate);
  }

  public function headings(): array
  {
    return [
      'No',
      'Nama',
      'Email',
      'No. Telepon',
      'Jumlah Peminjaman',
      'Status',
    ];
  }

  public function map($user): array
  {
    static $no = 0;
    $no++;

    return [
      $no,
      $user->name,
      $user->email,
      $user->phone ?? '-',
      $user->borrowings_count,
      $user->status ?? 'active',
    ];
  }

  public function styles(Worksheet $sheet): array
  {
    return [
      1 => ['font' => ['bold' => true]],
    ];
  }
}
