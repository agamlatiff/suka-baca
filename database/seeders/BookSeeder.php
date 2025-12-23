<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $books = [
      // Fiksi
      [
        'title' => 'Laskar Pelangi',
        'author' => 'Andrea Hirata',
        'category' => 'Fiksi',
        'description' => 'Novel inspiratif tentang perjuangan anak-anak Belitung untuk meraih pendidikan.',
        'rental_fee' => 5000,
        'copies' => 3,
      ],
      [
        'title' => 'Bumi Manusia',
        'author' => 'Pramoedya Ananta Toer',
        'category' => 'Fiksi',
        'description' => 'Novel sejarah yang menceritakan kehidupan di era kolonial Belanda.',
        'rental_fee' => 7000,
        'copies' => 2,
      ],
      [
        'title' => 'Tenggelamnya Kapal Van Der Wijck',
        'author' => 'Hamka',
        'category' => 'Fiksi',
        'description' => 'Kisah cinta tragis yang mempertanyakan adat dan tradisi.',
        'rental_fee' => 5000,
        'copies' => 3,
      ],
      // Non-Fiksi
      [
        'title' => 'Sapiens: Sejarah Singkat Umat Manusia',
        'author' => 'Yuval Noah Harari',
        'category' => 'Non-Fiksi',
        'description' => 'Eksplorasi sejarah manusia dari zaman prasejarah hingga modern.',
        'rental_fee' => 8000,
        'copies' => 2,
      ],
      [
        'title' => 'Atomic Habits',
        'author' => 'James Clear',
        'category' => 'Pengembangan Diri',
        'description' => 'Panduan membangun kebiasaan baik dan menghilangkan kebiasaan buruk.',
        'rental_fee' => 6000,
        'copies' => 4,
      ],
      // Anak-Anak
      [
        'title' => 'Si Kancil dan Buaya',
        'author' => 'Cerita Rakyat Indonesia',
        'category' => 'Anak-Anak',
        'description' => 'Dongeng klasik Indonesia tentang kecerdikan Si Kancil.',
        'rental_fee' => 3000,
        'copies' => 5,
      ],
      [
        'title' => 'Petualangan Timun Mas',
        'author' => 'Cerita Rakyat Jawa',
        'category' => 'Anak-Anak',
        'description' => 'Kisah gadis pemberani yang mengalahkan raksasa jahat.',
        'rental_fee' => 3000,
        'copies' => 4,
      ],
      // Pendidikan
      [
        'title' => 'Matematika SMA Kelas 12',
        'author' => 'Tim Penyusun',
        'category' => 'Pendidikan',
        'description' => 'Buku pelajaran matematika untuk siswa SMA kelas 12.',
        'rental_fee' => 4000,
        'copies' => 6,
      ],
      [
        'title' => 'Bahasa Indonesia untuk Pemula',
        'author' => 'Dr. Ahmad Sudrajat',
        'category' => 'Pendidikan',
        'description' => 'Panduan belajar bahasa Indonesia yang baik dan benar.',
        'rental_fee' => 4000,
        'copies' => 3,
      ],
      // Komik & Manga
      [
        'title' => 'One Piece Vol. 1',
        'author' => 'Eiichiro Oda',
        'category' => 'Komik & Manga',
        'description' => 'Petualangan Monkey D. Luffy mencari harta karun One Piece.',
        'rental_fee' => 3000,
        'copies' => 5,
      ],
      [
        'title' => 'Naruto Vol. 1',
        'author' => 'Masashi Kishimoto',
        'category' => 'Komik & Manga',
        'description' => 'Kisah ninja muda Naruto Uzumaki mengejar mimpinya.',
        'rental_fee' => 3000,
        'copies' => 4,
      ],
      // Bisnis
      [
        'title' => 'Rich Dad Poor Dad',
        'author' => 'Robert Kiyosaki',
        'category' => 'Bisnis',
        'description' => 'Pelajaran keuangan dari dua perspektif ayah yang berbeda.',
        'rental_fee' => 6000,
        'copies' => 3,
      ],
      // Sejarah
      [
        'title' => 'Sejarah Indonesia Modern',
        'author' => 'M.C. Ricklefs',
        'category' => 'Sejarah',
        'description' => 'Catatan sejarah Indonesia dari abad ke-16 hingga sekarang.',
        'rental_fee' => 7000,
        'copies' => 2,
      ],
      // Kesehatan
      [
        'title' => 'Hidup Sehat dengan Pola Makan',
        'author' => 'Dr. Tan Shot Yen',
        'category' => 'Kesehatan',
        'description' => 'Panduan pola makan sehat untuk kehidupan yang lebih baik.',
        'rental_fee' => 5000,
        'copies' => 3,
      ],
      // Sastra Klasik
      [
        'title' => 'Siti Nurbaya',
        'author' => 'Marah Rusli',
        'category' => 'Sastra Klasik',
        'description' => 'Kisah cinta yang terhalang oleh perjodohan adat Minangkabau.',
        'rental_fee' => 5000,
        'copies' => 2,
      ],
    ];

    foreach ($books as $bookData) {
      // Get category
      $category = Category::where('name', $bookData['category'])->first();

      if (!$category) {
        continue;
      }

      // Create book
      $book = Book::firstOrCreate(
        ['title' => $bookData['title']],
        [
          'author' => $bookData['author'],
          'category_id' => $category->id,
          'description' => $bookData['description'],
          'rental_fee' => $bookData['rental_fee'],
          'total_copies' => $bookData['copies'],
          'available_copies' => $bookData['copies'],
          'times_borrowed' => 0,
        ]
      );

      // Create copies if book was just created
      if ($book->wasRecentlyCreated) {
        for ($i = 1; $i <= $bookData['copies']; $i++) {
          BookCopy::create([
            'book_id' => $book->id,
            'copy_code' => sprintf('BK%03d-C%02d', $book->id, $i),
            'status' => 'available',
            'notes' => null,
          ]);
        }
      }
    }
  }
}
