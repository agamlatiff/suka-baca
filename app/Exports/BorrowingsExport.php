<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BorrowingsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
  use Exportable;

  protected $startDate;
  protected $endDate;

  public function __construct($startDate = null, $endDate = null)
  {
    $this->startDate = $startDate ?? now()->startOfMonth()->format('Y-m-d');
    $this->endDate = $endDate ?? now()->endOfMonth()->format('Y-m-d');
  }

  public function query()
  {
    return Borrowing::query()
      ->with(['user', 'book', 'bookCopy'])
      ->whereBetween('borrowed_at', [$this->startDate, $this->endDate])
      ->latest('borrowed_at');
  }

  public function headings(): array
  {
    return [
      'Kode Peminjaman',
      'Peminjam',
      'Email',
      'Judul Buku',
      'Kode Eksemplar',
      'Tanggal Pinjam',
      'Jatuh Tempo',
      'Tanggal Kembali',
      'Status',
      'Biaya Sewa',
      'Denda',
      'Total Biaya',
      'Lunas',
    ];
  }

  public function map($borrowing): array
  {
    return [
      $borrowing->borrowing_code ?? $borrowing->code,
      $borrowing->user?->name ?? '-',
      $borrowing->user?->email ?? '-',
      $borrowing->book?->title ?? '-',
      $borrowing->bookCopy?->copy_code ?? '-',
      $borrowing->borrowed_at?->format('d/m/Y') ?? '-',
      $borrowing->due_date?->format('d/m/Y') ?? '-',
      $borrowing->returned_at?->format('d/m/Y') ?? '-',
      match ($borrowing->status) {
        'active' => 'Aktif',
        'returned' => 'Dikembalikan',
        'overdue' => 'Terlambat',
        'pending' => 'Menunggu',
        default => $borrowing->status,
      },
      $borrowing->rental_fee,
      $borrowing->late_fee + ($borrowing->damage_fee ?? 0),
      $borrowing->total_fee,
      $borrowing->is_paid ? 'Ya' : 'Tidak',
    ];
  }

  public function styles(Worksheet $sheet): array
  {
    return [
      1 => ['font' => ['bold' => true]],
    ];
  }
}
