<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $categories = [
      [
        'name' => 'Fiksi',
        'description' => 'Novel, cerita pendek, dan karya fiksi sastra',
      ],
      [
        'name' => 'Non-Fiksi',
        'description' => 'Buku fakta termasuk biografi, sejarah, dan sains',
      ],
      [
        'name' => 'Anak-Anak',
        'description' => 'Buku untuk anak-anak dan pembaca muda',
      ],
      [
        'name' => 'Pendidikan',
        'description' => 'Buku teks, panduan belajar, dan materi pendidikan',
      ],
      [
        'name' => 'Komik & Manga',
        'description' => 'Buku komik, novel grafis, dan manga',
      ],
      [
        'name' => 'Agama',
        'description' => 'Teks keagamaan dan buku spiritual',
      ],
      [
        'name' => 'Pengembangan Diri',
        'description' => 'Buku pengembangan pribadi dan motivasi',
      ],
      [
        'name' => 'Bisnis',
        'description' => 'Bisnis, keuangan, dan kewirausahaan',
      ],
      [
        'name' => 'Sains & Teknologi',
        'description' => 'Ilmu pengetahuan, teknologi, dan inovasi',
      ],
      [
        'name' => 'Sejarah',
        'description' => 'Buku sejarah Indonesia dan dunia',
      ],
      [
        'name' => 'Kesehatan',
        'description' => 'Kesehatan, kedokteran, dan gaya hidup sehat',
      ],
      [
        'name' => 'Memasak',
        'description' => 'Resep masakan, kuliner, dan tips dapur',
      ],
      [
        'name' => 'Hobi & Kerajinan',
        'description' => 'Hobi, kerajinan tangan, dan aktivitas kreatif',
      ],
      [
        'name' => 'Perjalanan',
        'description' => 'Panduan wisata, travel, dan petualangan',
      ],
      [
        'name' => 'Sastra Klasik',
        'description' => 'Karya sastra klasik Indonesia dan dunia',
      ],
    ];

    foreach ($categories as $category) {
      Category::firstOrCreate(
        ['name' => $category['name']],
        ['description' => $category['description']]
      );
    }
  }
}
