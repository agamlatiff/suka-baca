<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class BooksImport implements ToModel, WithHeadingRow, WithValidation
{
  public function model(array $row)
  {
    // Find or create category
    $category = null;
    if (!empty($row['kategori'])) {
      $category = Category::firstOrCreate(
        ['name' => $row['kategori']],
        ['slug' => Str::slug($row['kategori'])]
      );
    }

    return new Book([
      'title' => $row['judul'],
      'slug' => Str::slug($row['judul']) . '-' . uniqid(),
      'author' => $row['penulis'],
      'category_id' => $category?->id,
      'publisher' => $row['penerbit'] ?? null,
      'year' => $row['tahun'] ?? null,
      'isbn' => $row['isbn'] ?? null,
      'description' => $row['deskripsi'] ?? null,
      'rental_fee' => $row['biaya_sewa'] ?? 5000,
      'book_price' => $row['harga_buku'] ?? 0,
      'total_copies' => $row['total_eksemplar'] ?? 1,
      'available_copies' => $row['eksemplar_tersedia'] ?? $row['total_eksemplar'] ?? 1,
    ]);
  }

  public function rules(): array
  {
    return [
      'judul' => 'required|string|max:255',
      'penulis' => 'required|string|max:255',
      'kategori' => 'nullable|string|max:255',
      'penerbit' => 'nullable|string|max:255',
      'tahun' => 'nullable|integer|min:1900|max:2100',
      'isbn' => 'nullable|string|max:20',
      'deskripsi' => 'nullable|string',
      'biaya_sewa' => 'nullable|numeric|min:0',
      'harga_buku' => 'nullable|numeric|min:0',
      'total_eksemplar' => 'nullable|integer|min:1',
    ];
  }
}
