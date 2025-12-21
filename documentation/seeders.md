# Sukabaca - Seeders & Factories

## Overview

Seeders and factories are used to populate the database with test/initial data.

```
database/
├── factories/
│   ├── UserFactory.php
│   ├── CategoryFactory.php
│   ├── BookFactory.php
│   ├── BookCopyFactory.php
│   └── BorrowingFactory.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── UserSeeder.php
    ├── CategorySeeder.php
    ├── BookSeeder.php
    └── BorrowingSeeder.php
```

---

## 1. Factories

### UserFactory

```php
// database/factories/UserFactory.php

public function definition(): array
{
    return [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'role' => 'user',
        'phone' => fake()->phoneNumber(),
        'remember_token' => Str::random(10),
    ];
}

// Admin state
public function admin(): static
{
    return $this->state(fn (array $attributes) => [
        'role' => 'admin',
    ]);
}
```

### CategoryFactory

```php
// database/factories/CategoryFactory.php

public function definition(): array
{
    return [
        'name' => fake()->unique()->word(),
        'description' => fake()->sentence(),
    ];
}
```

### BookFactory

```php
// database/factories/BookFactory.php

public function definition(): array
{
    return [
        'title' => fake()->sentence(3),
        'author' => fake()->name(),
        'category_id' => Category::factory(),
        'isbn' => fake()->unique()->isbn13(),
        'description' => fake()->paragraph(),
        'total_copies' => 0,
        'available_copies' => 0,
        'times_borrowed' => 0,
    ];
}
```

### BookCopyFactory

```php
// database/factories/BookCopyFactory.php

public function definition(): array
{
    $book = Book::inRandomOrder()->first() ?? Book::factory()->create();

    return [
        'book_id' => $book->id,
        'copy_code' => BookCopy::generateCopyCode($book),
        'status' => 'available',
        'notes' => null,
    ];
}

public function borrowed(): static
{
    return $this->state(fn (array $attributes) => [
        'status' => 'borrowed',
    ]);
}
```

### BorrowingFactory

```php
// database/factories/BorrowingFactory.php

public function definition(): array
{
    $borrowedAt = fake()->dateTimeBetween('-30 days', 'now');
    $dueDate = (clone $borrowedAt)->modify('+14 days');

    return [
        'borrowing_code' => 'BRW-' . now()->format('Ymd') . '-' . fake()->unique()->randomNumber(3),
        'user_id' => User::factory(),
        'book_copy_id' => BookCopy::factory(),
        'borrowed_at' => $borrowedAt,
        'due_date' => $dueDate,
        'returned_at' => null,
        'rental_fee' => 10000,
        'late_fee' => 0,
        'total_fee' => 10000,
        'is_paid' => false,
        'status' => 'active',
        'days_late' => 0,
    ];
}

public function returned(): static
{
    return $this->state(fn (array $attributes) => [
        'returned_at' => now(),
        'status' => 'returned',
        'is_paid' => true,
    ]);
}

public function overdue(): static
{
    return $this->state(fn (array $attributes) => [
        'borrowed_at' => now()->subDays(20),
        'due_date' => now()->subDays(6),
        'status' => 'overdue',
        'days_late' => 6,
        'late_fee' => 12000,
        'total_fee' => 22000,
    ]);
}
```

---

## 2. Seeders

### UserSeeder

```php
// database/seeders/UserSeeder.php

public function run(): void
{
    // Create admin
    User::factory()->admin()->create([
        'name' => 'Admin Sukabaca',
        'email' => 'admin@sukabaca.com',
        'password' => Hash::make('admin123'),
    ]);

    // Create sample users
    User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    User::factory()->create([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'password' => Hash::make('password'),
    ]);

    // Create random users
    User::factory(10)->create();
}
```

### CategorySeeder

```php
// database/seeders/CategorySeeder.php

public function run(): void
{
    $categories = [
        ['name' => 'Novel', 'description' => 'Fiksi naratif dalam bentuk prosa'],
        ['name' => 'Komik', 'description' => 'Cerita bergambar'],
        ['name' => 'Sains', 'description' => 'Buku ilmu pengetahuan'],
        ['name' => 'Sejarah', 'description' => 'Buku tentang sejarah'],
        ['name' => 'Biografi', 'description' => 'Kisah hidup tokoh'],
        ['name' => 'Teknologi', 'description' => 'Buku tentang teknologi dan komputer'],
        ['name' => 'Agama', 'description' => 'Buku keagamaan'],
        ['name' => 'Anak-anak', 'description' => 'Buku untuk anak-anak'],
        ['name' => 'Bisnis', 'description' => 'Buku bisnis dan manajemen'],
        ['name' => 'Self-Help', 'description' => 'Buku pengembangan diri'],
    ];

    foreach ($categories as $category) {
        Category::create($category);
    }
}
```

### BookSeeder

```php
// database/seeders/BookSeeder.php

public function run(): void
{
    $books = [
        [
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'category' => 'Novel',
            'isbn' => '9789793062792',
            'copies' => 5,
        ],
        [
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer',
            'category' => 'Novel',
            'isbn' => '9789799731237',
            'copies' => 3,
        ],
        [
            'title' => 'Sapiens: A Brief History of Humankind',
            'author' => 'Yuval Noah Harari',
            'category' => 'Sejarah',
            'isbn' => '9780062316097',
            'copies' => 4,
        ],
        [
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'category' => 'Self-Help',
            'isbn' => '9780735211292',
            'copies' => 6,
        ],
        [
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'category' => 'Teknologi',
            'isbn' => '9780132350884',
            'copies' => 2,
        ],
    ];

    foreach ($books as $bookData) {
        $category = Category::where('name', $bookData['category'])->first();

        $book = Book::create([
            'title' => $bookData['title'],
            'author' => $bookData['author'],
            'category_id' => $category->id,
            'isbn' => $bookData['isbn'],
            'total_copies' => $bookData['copies'],
            'available_copies' => $bookData['copies'],
        ]);

        // Create copies
        for ($i = 1; $i <= $bookData['copies']; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'copy_code' => 'BK' . str_pad($book->id, 3, '0', STR_PAD_LEFT) . '-C' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => 'available',
            ]);
        }
    }
}
```

### DatabaseSeeder

```php
// database/seeders/DatabaseSeeder.php

public function run(): void
{
    $this->call([
        UserSeeder::class,
        CategorySeeder::class,
        BookSeeder::class,
    ]);
}
```

---

## Running Seeders

### Fresh migration with seeding

```bash
php artisan migrate:fresh --seed
```

### Run specific seeder

```bash
php artisan db:seed --class=CategorySeeder
```

### Run all seeders

```bash
php artisan db:seed
```

---

## Test Data Summary

After seeding:

| Table       | Records | Notes                         |
| ----------- | ------- | ----------------------------- |
| users       | 13      | 1 admin + 2 named + 10 random |
| categories  | 10      | Predefined categories         |
| books       | 5       | Sample books                  |
| book_copies | 20      | Distributed across books      |
| borrowings  | 0       | Start fresh                   |

### Default Credentials

| Role  | Email              | Password |
| ----- | ------------------ | -------- |
| Admin | admin@sukabaca.com | admin123 |
| User  | john@example.com   | password |
| User  | jane@example.com   | password |
