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
   * Generates 50+ books with realistic data across all categories
   */
  public function run(): void
  {
    $books = [
      // ==================== FIKSI (10 buku) ====================
      [
        'title' => 'Laskar Pelangi',
        'author' => 'Andrea Hirata',
        'category' => 'Fiksi',
        'description' => 'Novel inspiratif tentang perjuangan anak-anak Belitung untuk meraih pendidikan. Kisah persahabatan dan semangat pantang menyerah.',
        'rental_fee' => 5000,
        'copies' => 3,
        'times_borrowed' => rand(15, 50),
      ],
      [
        'title' => 'Bumi Manusia',
        'author' => 'Pramoedya Ananta Toer',
        'category' => 'Fiksi',
        'description' => 'Novel sejarah tentang kehidupan di era kolonial Belanda. Kisah cinta Minke dan Annelies.',
        'rental_fee' => 7000,
        'copies' => 2,
        'times_borrowed' => rand(20, 60),
      ],
      [
        'title' => 'Tenggelamnya Kapal Van Der Wijck',
        'author' => 'Hamka',
        'category' => 'Fiksi',
        'description' => 'Kisah cinta tragis yang mempertanyakan adat dan tradisi Minangkabau.',
        'rental_fee' => 5000,
        'copies' => 3,
        'times_borrowed' => rand(10, 40),
      ],
      [
        'title' => 'Dilan 1990',
        'author' => 'Pidi Baiq',
        'category' => 'Fiksi',
        'description' => 'Kisah cinta romantis antara Milea dan Dilan di Bandung tahun 1990.',
        'rental_fee' => 5000,
        'copies' => 4,
        'times_borrowed' => rand(25, 70),
      ],
      [
        'title' => 'Perahu Kertas',
        'author' => 'Dee Lestari',
        'category' => 'Fiksi',
        'description' => 'Novel tentang mimpi, cinta, dan seni. Kisah Kugy dan Keenan.',
        'rental_fee' => 6000,
        'copies' => 3,
        'times_borrowed' => rand(15, 45),
      ],
      [
        'title' => 'Ayat-Ayat Cinta',
        'author' => 'Habiburrahman El Shirazy',
        'category' => 'Fiksi',
        'description' => 'Kisah cinta Fahri di Mesir dengan latar belakang keislaman yang kuat.',
        'rental_fee' => 6000,
        'copies' => 3,
        'times_borrowed' => rand(20, 55),
      ],
      [
        'title' => 'Negeri 5 Menara',
        'author' => 'Ahmad Fuadi',
        'category' => 'Fiksi',
        'description' => 'Kisah persahabatan dan perjuangan di pesantren Gontor.',
        'rental_fee' => 5000,
        'copies' => 3,
        'times_borrowed' => rand(15, 40),
      ],
      [
        'title' => 'Supernova: Ksatria, Puteri, dan Bintang Jatuh',
        'author' => 'Dee Lestari',
        'category' => 'Fiksi',
        'description' => 'Novel fiksi ilmiah pertama dari seri Supernova yang fenomenal.',
        'rental_fee' => 7000,
        'copies' => 2,
        'times_borrowed' => rand(10, 35),
      ],
      [
        'title' => 'Pulang',
        'author' => 'Tere Liye',
        'category' => 'Fiksi',
        'description' => 'Kisah seorang konsultan keuangan yang menemukan jati dirinya.',
        'rental_fee' => 6000,
        'copies' => 3,
        'times_borrowed' => rand(18, 50),
      ],
      [
        'title' => 'Harry Potter dan Batu Bertuah',
        'author' => 'J.K. Rowling',
        'category' => 'Fiksi',
        'description' => 'Petualangan Harry Potter pertama kali memasuki dunia sihir Hogwarts.',
        'rental_fee' => 6000,
        'copies' => 5,
        'times_borrowed' => rand(30, 80),
      ],

      // ==================== NON-FIKSI (5 buku) ====================
      [
        'title' => 'Sapiens: Sejarah Singkat Umat Manusia',
        'author' => 'Yuval Noah Harari',
        'category' => 'Non-Fiksi',
        'description' => 'Eksplorasi sejarah manusia dari zaman prasejarah hingga modern.',
        'rental_fee' => 8000,
        'copies' => 2,
        'times_borrowed' => rand(15, 45),
      ],
      [
        'title' => 'Homo Deus',
        'author' => 'Yuval Noah Harari',
        'category' => 'Non-Fiksi',
        'description' => 'Masa depan umat manusia dan tantangan yang akan dihadapi.',
        'rental_fee' => 8000,
        'copies' => 2,
        'times_borrowed' => rand(10, 30),
      ],
      [
        'title' => '21 Lessons for the 21st Century',
        'author' => 'Yuval Noah Harari',
        'category' => 'Non-Fiksi',
        'description' => '21 pelajaran penting untuk menghadapi abad ke-21.',
        'rental_fee' => 8000,
        'copies' => 2,
        'times_borrowed' => rand(8, 25),
      ],
      [
        'title' => 'Educated',
        'author' => 'Tara Westover',
        'category' => 'Non-Fiksi',
        'description' => 'Memoir inspiratif tentang kekuatan pendidikan dan keberanian.',
        'rental_fee' => 7000,
        'copies' => 2,
        'times_borrowed' => rand(12, 35),
      ],
      [
        'title' => 'Guns, Germs, and Steel',
        'author' => 'Jared Diamond',
        'category' => 'Non-Fiksi',
        'description' => 'Mengapa beberapa peradaban lebih maju dari yang lain.',
        'rental_fee' => 8000,
        'copies' => 2,
        'times_borrowed' => rand(8, 22),
      ],

      // ==================== ANAK-ANAK (5 buku) ====================
      [
        'title' => 'Si Kancil dan Buaya',
        'author' => 'Cerita Rakyat Indonesia',
        'category' => 'Anak-Anak',
        'description' => 'Dongeng klasik tentang kecerdikan Si Kancil.',
        'rental_fee' => 3000,
        'copies' => 5,
        'times_borrowed' => rand(20, 60),
      ],
      [
        'title' => 'Petualangan Timun Mas',
        'author' => 'Cerita Rakyat Jawa',
        'category' => 'Anak-Anak',
        'description' => 'Kisah gadis pemberani yang mengalahkan raksasa jahat.',
        'rental_fee' => 3000,
        'copies' => 4,
        'times_borrowed' => rand(18, 50),
      ],
      [
        'title' => 'Malin Kundang',
        'author' => 'Cerita Rakyat Sumatra',
        'category' => 'Anak-Anak',
        'description' => 'Legenda anak durhaka dari Sumatra Barat.',
        'rental_fee' => 3000,
        'copies' => 4,
        'times_borrowed' => rand(15, 45),
      ],
      [
        'title' => 'Keong Emas',
        'author' => 'Cerita Rakyat Jawa',
        'category' => 'Anak-Anak',
        'description' => 'Kisah putri yang dikutuk menjadi keong emas.',
        'rental_fee' => 3000,
        'copies' => 4,
        'times_borrowed' => rand(12, 40),
      ],
      [
        'title' => 'Bawang Merah Bawang Putih',
        'author' => 'Cerita Rakyat Indonesia',
        'category' => 'Anak-Anak',
        'description' => 'Cerita tentang kebaikan selalu menang melawan kejahatan.',
        'rental_fee' => 3000,
        'copies' => 5,
        'times_borrowed' => rand(20, 55),
      ],

      // ==================== PENDIDIKAN (5 buku) ====================
      [
        'title' => 'Matematika SMA Kelas 12',
        'author' => 'Tim Penyusun Kemendikbud',
        'category' => 'Pendidikan',
        'description' => 'Buku pelajaran matematika untuk siswa SMA kelas 12.',
        'rental_fee' => 4000,
        'copies' => 6,
        'times_borrowed' => rand(25, 70),
      ],
      [
        'title' => 'Bahasa Indonesia untuk Pemula',
        'author' => 'Dr. Ahmad Sudrajat',
        'category' => 'Pendidikan',
        'description' => 'Panduan belajar bahasa Indonesia yang baik dan benar.',
        'rental_fee' => 4000,
        'copies' => 3,
        'times_borrowed' => rand(12, 35),
      ],
      [
        'title' => 'Fisika Dasar untuk Universitas',
        'author' => 'Prof. Dr. Sutrisno',
        'category' => 'Pendidikan',
        'description' => 'Buku fisika dasar untuk mahasiswa tahun pertama.',
        'rental_fee' => 5000,
        'copies' => 4,
        'times_borrowed' => rand(18, 50),
      ],
      [
        'title' => 'Kimia Organik',
        'author' => 'Dr. Fessenden',
        'category' => 'Pendidikan',
        'description' => 'Panduan lengkap kimia organik untuk perguruan tinggi.',
        'rental_fee' => 6000,
        'copies' => 3,
        'times_borrowed' => rand(10, 30),
      ],
      [
        'title' => 'TOEFL Preparation Guide',
        'author' => 'Educational Testing Service',
        'category' => 'Pendidikan',
        'description' => 'Panduan lengkap persiapan tes TOEFL.',
        'rental_fee' => 5000,
        'copies' => 4,
        'times_borrowed' => rand(20, 55),
      ],

      // ==================== KOMIK & MANGA (6 buku) ====================
      [
        'title' => 'One Piece Vol. 1',
        'author' => 'Eiichiro Oda',
        'category' => 'Komik & Manga',
        'description' => 'Petualangan Monkey D. Luffy mencari harta karun One Piece.',
        'rental_fee' => 3000,
        'copies' => 5,
        'times_borrowed' => rand(40, 100),
      ],
      [
        'title' => 'Naruto Vol. 1',
        'author' => 'Masashi Kishimoto',
        'category' => 'Komik & Manga',
        'description' => 'Kisah ninja muda Naruto Uzumaki mengejar mimpinya.',
        'rental_fee' => 3000,
        'copies' => 4,
        'times_borrowed' => rand(35, 90),
      ],
      [
        'title' => 'Attack on Titan Vol. 1',
        'author' => 'Hajime Isayama',
        'category' => 'Komik & Manga',
        'description' => 'Pertempuran umat manusia melawan para titan pemakan manusia.',
        'rental_fee' => 3500,
        'copies' => 4,
        'times_borrowed' => rand(30, 80),
      ],
      [
        'title' => 'Dragon Ball Vol. 1',
        'author' => 'Akira Toriyama',
        'category' => 'Komik & Manga',
        'description' => 'Petualangan Son Goku mencari bola naga legendaris.',
        'rental_fee' => 3000,
        'copies' => 3,
        'times_borrowed' => rand(25, 65),
      ],
      [
        'title' => 'Demon Slayer Vol. 1',
        'author' => 'Koyoharu Gotouge',
        'category' => 'Komik & Manga',
        'description' => 'Tanjiro menjadi pemburu iblis untuk menyelamatkan adiknya.',
        'rental_fee' => 3500,
        'copies' => 4,
        'times_borrowed' => rand(35, 85),
      ],
      [
        'title' => 'Doraemon Vol. 1',
        'author' => 'Fujiko F. Fujio',
        'category' => 'Komik & Manga',
        'description' => 'Robot kucing dari masa depan dengan kantong ajaib.',
        'rental_fee' => 3000,
        'copies' => 5,
        'times_borrowed' => rand(30, 75),
      ],

      // ==================== PENGEMBANGAN DIRI (5 buku) ====================
      [
        'title' => 'Atomic Habits',
        'author' => 'James Clear',
        'category' => 'Pengembangan Diri',
        'description' => 'Panduan membangun kebiasaan baik dan menghilangkan yang buruk.',
        'rental_fee' => 6000,
        'copies' => 4,
        'times_borrowed' => rand(35, 90),
      ],
      [
        'title' => 'Sebuah Seni Untuk Bersikap Bodo Amat',
        'author' => 'Mark Manson',
        'category' => 'Pengembangan Diri',
        'description' => 'Pendekatan berbeda untuk menjalani hidup yang lebih baik.',
        'rental_fee' => 6000,
        'copies' => 4,
        'times_borrowed' => rand(30, 80),
      ],
      [
        'title' => 'Mindset: The New Psychology of Success',
        'author' => 'Carol S. Dweck',
        'category' => 'Pengembangan Diri',
        'description' => 'Bagaimana pola pikir mempengaruhi kesuksesan kita.',
        'rental_fee' => 6000,
        'copies' => 3,
        'times_borrowed' => rand(20, 55),
      ],
      [
        'title' => 'The 7 Habits of Highly Effective People',
        'author' => 'Stephen R. Covey',
        'category' => 'Pengembangan Diri',
        'description' => '7 kebiasaan orang-orang yang sangat efektif.',
        'rental_fee' => 7000,
        'copies' => 3,
        'times_borrowed' => rand(25, 65),
      ],
      [
        'title' => 'Deep Work',
        'author' => 'Cal Newport',
        'category' => 'Pengembangan Diri',
        'description' => 'Aturan untuk fokus sukses di dunia yang penuh distraksi.',
        'rental_fee' => 6000,
        'copies' => 3,
        'times_borrowed' => rand(15, 45),
      ],

      // ==================== BISNIS (5 buku) ====================
      [
        'title' => 'Rich Dad Poor Dad',
        'author' => 'Robert Kiyosaki',
        'category' => 'Bisnis',
        'description' => 'Pelajaran keuangan dari dua perspektif ayah yang berbeda.',
        'rental_fee' => 6000,
        'copies' => 3,
        'times_borrowed' => rand(30, 75),
      ],
      [
        'title' => 'The Psychology of Money',
        'author' => 'Morgan Housel',
        'category' => 'Bisnis',
        'description' => 'Pelajaran tentang kekayaan, keserakahan, dan kebahagiaan.',
        'rental_fee' => 7000,
        'copies' => 3,
        'times_borrowed' => rand(25, 65),
      ],
      [
        'title' => 'Zero to One',
        'author' => 'Peter Thiel',
        'category' => 'Bisnis',
        'description' => 'Catatan tentang startup dan cara membangun masa depan.',
        'rental_fee' => 7000,
        'copies' => 2,
        'times_borrowed' => rand(18, 50),
      ],
      [
        'title' => 'The Lean Startup',
        'author' => 'Eric Ries',
        'category' => 'Bisnis',
        'description' => 'Metode untuk membuat produk dan bisnis yang sukses.',
        'rental_fee' => 7000,
        'copies' => 2,
        'times_borrowed' => rand(15, 45),
      ],
      [
        'title' => 'Think and Grow Rich',
        'author' => 'Napoleon Hill',
        'category' => 'Bisnis',
        'description' => 'Klasik tentang rahasia sukses finansial.',
        'rental_fee' => 5000,
        'copies' => 3,
        'times_borrowed' => rand(22, 60),
      ],

      // ==================== SEJARAH (4 buku) ====================
      [
        'title' => 'Sejarah Indonesia Modern',
        'author' => 'M.C. Ricklefs',
        'category' => 'Sejarah',
        'description' => 'Sejarah Indonesia dari abad ke-16 hingga sekarang.',
        'rental_fee' => 7000,
        'copies' => 2,
        'times_borrowed' => rand(10, 30),
      ],
      [
        'title' => 'Indonesia: Sejarah & Budaya',
        'author' => 'Prof. Dr. Koentjaraningrat',
        'category' => 'Sejarah',
        'description' => 'Kajian mendalam tentang sejarah dan budaya Indonesia.',
        'rental_fee' => 6000,
        'copies' => 2,
        'times_borrowed' => rand(8, 25),
      ],
      [
        'title' => 'Nusantara: Sejarah Indonesia',
        'author' => 'Bernard H.M. Vlekke',
        'category' => 'Sejarah',
        'description' => 'Sejarah lengkap nusantara dari masa kuno.',
        'rental_fee' => 7000,
        'copies' => 2,
        'times_borrowed' => rand(6, 20),
      ],
      [
        'title' => 'Dari Penjara ke Penjara',
        'author' => 'Tan Malaka',
        'category' => 'Sejarah',
        'description' => 'Autobiografi tokoh pergerakan kemerdekaan Indonesia.',
        'rental_fee' => 5000,
        'copies' => 2,
        'times_borrowed' => rand(8, 22),
      ],

      // ==================== KESEHATAN (4 buku) ====================
      [
        'title' => 'Hidup Sehat dengan Pola Makan',
        'author' => 'Dr. Tan Shot Yen',
        'category' => 'Kesehatan',
        'description' => 'Panduan pola makan sehat untuk kehidupan lebih baik.',
        'rental_fee' => 5000,
        'copies' => 3,
        'times_borrowed' => rand(15, 40),
      ],
      [
        'title' => 'Why We Sleep',
        'author' => 'Matthew Walker',
        'category' => 'Kesehatan',
        'description' => 'Ilmu di balik tidur dan pentingnya untuk kesehatan.',
        'rental_fee' => 6000,
        'copies' => 2,
        'times_borrowed' => rand(12, 35),
      ],
      [
        'title' => 'The Body: A Guide for Occupants',
        'author' => 'Bill Bryson',
        'category' => 'Kesehatan',
        'description' => 'Panduan lengkap tentang tubuh manusia.',
        'rental_fee' => 7000,
        'copies' => 2,
        'times_borrowed' => rand(10, 30),
      ],
      [
        'title' => 'Brain Rules',
        'author' => 'John Medina',
        'category' => 'Kesehatan',
        'description' => '12 prinsip untuk bertahan dan berkembang.',
        'rental_fee' => 6000,
        'copies' => 2,
        'times_borrowed' => rand(8, 25),
      ],

      // ==================== SASTRA KLASIK (4 buku) ====================
      [
        'title' => 'Siti Nurbaya',
        'author' => 'Marah Rusli',
        'category' => 'Sastra Klasik',
        'description' => 'Kisah cinta yang terhalang perjodohan adat Minangkabau.',
        'rental_fee' => 5000,
        'copies' => 2,
        'times_borrowed' => rand(12, 35),
      ],
      [
        'title' => 'Layar Terkembang',
        'author' => 'Sutan Takdir Alisjahbana',
        'category' => 'Sastra Klasik',
        'description' => 'Novel tentang emansipasi wanita Indonesia.',
        'rental_fee' => 5000,
        'copies' => 2,
        'times_borrowed' => rand(8, 25),
      ],
      [
        'title' => 'Azab dan Sengsara',
        'author' => 'Merari Siregar',
        'category' => 'Sastra Klasik',
        'description' => 'Novel pertama berbahasa Indonesia.',
        'rental_fee' => 5000,
        'copies' => 2,
        'times_borrowed' => rand(6, 20),
      ],
      [
        'title' => 'Atheis',
        'author' => 'Achdiat K. Mihardja',
        'category' => 'Sastra Klasik',
        'description' => 'Pergulatan iman seorang pemuda Indonesia.',
        'rental_fee' => 5000,
        'copies' => 2,
        'times_borrowed' => rand(8, 22),
      ],

      // ==================== SAINS & TEKNOLOGI (4 buku) ====================
      [
        'title' => 'A Brief History of Time',
        'author' => 'Stephen Hawking',
        'category' => 'Sains & Teknologi',
        'description' => 'Penjelasan populer tentang kosmologi dan alam semesta.',
        'rental_fee' => 7000,
        'copies' => 2,
        'times_borrowed' => rand(15, 40),
      ],
      [
        'title' => 'The Code Breaker',
        'author' => 'Walter Isaacson',
        'category' => 'Sains & Teknologi',
        'description' => 'Kisah CRISPR dan masa depan rekayasa genetika.',
        'rental_fee' => 8000,
        'copies' => 2,
        'times_borrowed' => rand(10, 30),
      ],
      [
        'title' => 'Clean Code',
        'author' => 'Robert C. Martin',
        'category' => 'Sains & Teknologi',
        'description' => 'Panduan menulis kode yang bersih dan dapat dipelihara.',
        'rental_fee' => 8000,
        'copies' => 3,
        'times_borrowed' => rand(20, 55),
      ],
      [
        'title' => 'The Pragmatic Programmer',
        'author' => 'David Thomas & Andrew Hunt',
        'category' => 'Sains & Teknologi',
        'description' => 'Dari pemula menjadi programmer master.',
        'rental_fee' => 8000,
        'copies' => 3,
        'times_borrowed' => rand(18, 50),
      ],

      // ==================== AGAMA (3 buku) ====================
      [
        'title' => 'Tafsir Al-Quran Al-Azhar Jilid 1',
        'author' => 'Buya Hamka',
        'category' => 'Agama',
        'description' => 'Tafsir Al-Quran karya ulama besar Indonesia.',
        'rental_fee' => 5000,
        'copies' => 3,
        'times_borrowed' => rand(15, 40),
      ],
      [
        'title' => 'Ihya Ulumuddin',
        'author' => 'Imam Al-Ghazali',
        'category' => 'Agama',
        'description' => 'Karya monumental tentang ilmu-ilmu agama.',
        'rental_fee' => 6000,
        'copies' => 2,
        'times_borrowed' => rand(10, 30),
      ],
      [
        'title' => 'Fiqih Sunnah',
        'author' => 'Sayyid Sabiq',
        'category' => 'Agama',
        'description' => 'Panduan lengkap fiqih berdasarkan sunnah Nabi.',
        'rental_fee' => 5000,
        'copies' => 3,
        'times_borrowed' => rand(12, 35),
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
          'times_borrowed' => $bookData['times_borrowed'] ?? 0,
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
