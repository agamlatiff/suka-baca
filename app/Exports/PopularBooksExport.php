<?php

namespace App\Exports;

use App\Services\BookService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PopularBooksExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
  public function collection()
  {
    return app(BookService::class)->getPopularBooks(50);
  }

  public function headings(): array
  {
    return [
      'No',
      'Judul Buku',
      'Penulis',
      'Kategori',
      'Jumlah Dipinjam',
      'Stok Tersedia',
      'Harga Sewa',
    ];
  }

  public function map($book): array
  {
    static $no = 0;
    $no++;

    return [
      $no,
      $book->title,
      $book->author,
      $book->category->name ?? '-',
      $book->times_borrowed,
      $book->available_copies . '/' . $book->total_copies,
      'Rp ' . number_format($book->rental_fee, 0, ',', '.'),
    ];
  }

  public function styles(Worksheet $sheet): array
  {
    return [
      1 => ['font' => ['bold' => true]],
    ];
  }
}
