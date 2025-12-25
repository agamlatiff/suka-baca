<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
  public function collection()
  {
    return Book::with('category')->get();
  }

  public function headings(): array
  {
    return [
      'ID',
      'Judul',
      'Penulis',
      'Kategori',
      'Penerbit',
      'Tahun',
      'ISBN',
      'Deskripsi',
      'Biaya Sewa',
      'Harga Buku',
      'Total Eksemplar',
      'Eksemplar Tersedia',
      'Jumlah Dipinjam',
    ];
  }

  public function map($book): array
  {
    return [
      $book->id,
      $book->title,
      $book->author,
      $book->category?->name ?? '-',
      $book->publisher ?? '-',
      $book->year ?? '-',
      $book->isbn ?? '-',
      $book->description ?? '-',
      $book->rental_fee,
      $book->book_price ?? 0,
      $book->total_copies,
      $book->available_copies,
      $book->times_borrowed,
    ];
  }

  public function styles(Worksheet $sheet): array
  {
    return [
      1 => ['font' => ['bold' => true]],
    ];
  }
}
