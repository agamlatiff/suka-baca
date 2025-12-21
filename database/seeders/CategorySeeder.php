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
        'name' => 'Fiction',
        'description' => 'Novels, short stories, and literary fiction',
      ],
      [
        'name' => 'Non-Fiction',
        'description' => 'Factual books including biographies, history, and science',
      ],
      [
        'name' => 'Children',
        'description' => 'Books for children and young readers',
      ],
      [
        'name' => 'Education',
        'description' => 'Textbooks, study guides, and educational materials',
      ],
      [
        'name' => 'Comics & Manga',
        'description' => 'Comic books, graphic novels, and manga',
      ],
      [
        'name' => 'Religion',
        'description' => 'Religious texts and spiritual books',
      ],
      [
        'name' => 'Self-Help',
        'description' => 'Personal development and motivational books',
      ],
      [
        'name' => 'Business',
        'description' => 'Business, finance, and entrepreneurship',
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
