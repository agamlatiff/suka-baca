# Sukabaca - Testing Documentation

## Testing Framework

-   **PHPUnit** (included with Laravel)
-   **Pest** (optional, for cleaner syntax)

---

## Directory Structure

```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php
│   │   └── RegistrationTest.php
│   ├── Admin/
│   │   ├── BookControllerTest.php
│   │   ├── CategoryControllerTest.php
│   │   └── BorrowingControllerTest.php
│   ├── User/
│   │   ├── CatalogTest.php
│   │   └── BorrowingTest.php
│   └── ExampleTest.php
├── Unit/
│   ├── Models/
│   │   ├── BookTest.php
│   │   ├── BookCopyTest.php
│   │   └── BorrowingTest.php
│   ├── Services/
│   │   ├── BookServiceTest.php
│   │   └── BorrowingServiceTest.php
│   └── ExampleTest.php
└── TestCase.php
```

---

## Unit Tests

### BookTest

```php
<?php

namespace Tests\Unit\Models;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $book->category);
        $this->assertEquals($category->id, $book->category->id);
    }

    public function test_book_has_many_copies(): void
    {
        $book = Book::factory()->create();
        BookCopy::factory(3)->create(['book_id' => $book->id]);

        $this->assertCount(3, $book->copies);
    }

    public function test_is_available_returns_true_when_copies_exist(): void
    {
        $book = Book::factory()->create(['available_copies' => 5]);

        $this->assertTrue($book->isAvailable());
    }

    public function test_is_available_returns_false_when_no_copies(): void
    {
        $book = Book::factory()->create(['available_copies' => 0]);

        $this->assertFalse($book->isAvailable());
    }

    public function test_available_scope_filters_correctly(): void
    {
        Book::factory()->create(['available_copies' => 5]);
        Book::factory()->create(['available_copies' => 0]);
        Book::factory()->create(['available_copies' => 3]);

        $available = Book::available()->get();

        $this->assertCount(2, $available);
    }

    public function test_search_scope_finds_by_title(): void
    {
        Book::factory()->create(['title' => 'Harry Potter']);
        Book::factory()->create(['title' => 'Lord of the Rings']);

        $results = Book::search('Harry')->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Harry Potter', $results->first()->title);
    }

    public function test_search_scope_finds_by_author(): void
    {
        Book::factory()->create(['author' => 'J.K. Rowling']);
        Book::factory()->create(['author' => 'Tolkien']);

        $results = Book::search('Rowling')->get();

        $this->assertCount(1, $results);
    }
}
```

### BorrowingServiceTest

```php
<?php

namespace Tests\Unit\Services;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\User;
use App\Services\BorrowingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingServiceTest extends TestCase
{
    use RefreshDatabase;

    private BorrowingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(BorrowingService::class);
    }

    public function test_create_borrowing_success(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 1]);
        BookCopy::factory()->create([
            'book_id' => $book->id,
            'status' => 'available',
        ]);

        $borrowing = $this->service->createBorrowing($user, $book, 14);

        $this->assertNotNull($borrowing);
        $this->assertEquals($user->id, $borrowing->user_id);
        $this->assertEquals('active', $borrowing->status);
    }

    public function test_create_borrowing_fails_when_no_copies_available(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 0]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Tidak ada copy tersedia');

        $this->service->createBorrowing($user, $book, 14);
    }

    public function test_create_borrowing_fails_when_user_exceeded_limit(): void
    {
        $user = User::factory()->create();

        // Create 3 active borrowings (max limit)
        for ($i = 0; $i < 3; $i++) {
            $book = Book::factory()->create(['available_copies' => 1]);
            BookCopy::factory()->create([
                'book_id' => $book->id,
                'status' => 'available',
            ]);
            $this->service->createBorrowing($user, $book, 14);
        }

        // Try to borrow 4th book
        $book = Book::factory()->create(['available_copies' => 1]);
        BookCopy::factory()->create([
            'book_id' => $book->id,
            'status' => 'available',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Maksimal peminjaman');

        $this->service->createBorrowing($user, $book, 14);
    }

    public function test_return_book_success(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 1]);
        $copy = BookCopy::factory()->create([
            'book_id' => $book->id,
            'status' => 'available',
        ]);

        $borrowing = $this->service->createBorrowing($user, $book, 14);

        $this->assertEquals('borrowed', $copy->fresh()->status);

        $returned = $this->service->returnBook($borrowing);

        $this->assertEquals('returned', $returned->status);
        $this->assertNotNull($returned->returned_at);
        $this->assertEquals('available', $copy->fresh()->status);
    }
}
```

---

## Feature Tests

### CatalogTest

```php
<?php

namespace Tests\Feature\User;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_page_loads(): void
    {
        $response = $this->get('/catalog');
        $response->assertStatus(200);
    }

    public function test_catalog_shows_available_books(): void
    {
        $book = Book::factory()->create([
            'title' => 'Test Book',
            'available_copies' => 5,
        ]);

        $response = $this->get('/catalog');

        $response->assertSee('Test Book');
    }

    public function test_catalog_hides_unavailable_books(): void
    {
        $book = Book::factory()->create([
            'title' => 'Unavailable Book',
            'available_copies' => 0,
        ]);

        $response = $this->get('/catalog');

        $response->assertDontSee('Unavailable Book');
    }

    public function test_catalog_can_filter_by_category(): void
    {
        $category1 = Category::factory()->create(['name' => 'Novel']);
        $category2 = Category::factory()->create(['name' => 'Science']);

        Book::factory()->create([
            'title' => 'Novel Book',
            'category_id' => $category1->id,
            'available_copies' => 1,
        ]);
        Book::factory()->create([
            'title' => 'Science Book',
            'category_id' => $category2->id,
            'available_copies' => 1,
        ]);

        $response = $this->get('/catalog?category=' . $category1->id);

        $response->assertSee('Novel Book');
        $response->assertDontSee('Science Book');
    }

    public function test_catalog_can_search(): void
    {
        Book::factory()->create([
            'title' => 'Harry Potter',
            'available_copies' => 1,
        ]);
        Book::factory()->create([
            'title' => 'Lord of Rings',
            'available_copies' => 1,
        ]);

        $response = $this->get('/catalog?search=Harry');

        $response->assertSee('Harry Potter');
        $response->assertDontSee('Lord of Rings');
    }
}
```

### Admin\BookControllerTest

```php
<?php

namespace Tests\Feature\Admin;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_view_books_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/books');
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_view_books_list(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin/books');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_book(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/books', [
            'title' => 'New Book',
            'author' => 'Test Author',
            'category_id' => $category->id,
            'copies' => 3,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('books', ['title' => 'New Book']);
        $this->assertDatabaseCount('book_copies', 3);
    }

    public function test_admin_can_update_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->actingAs($this->admin)->put("/admin/books/{$book->id}", [
            'title' => 'Updated Title',
            'author' => $book->author,
            'category_id' => $book->category_id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('books', ['title' => 'Updated Title']);
    }

    public function test_admin_can_delete_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/books/{$book->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_cannot_delete_book_with_active_borrowings(): void
    {
        // Create book with active borrowing
        $book = Book::factory()->create();
        $copy = BookCopy::factory()->create([
            'book_id' => $book->id,
            'status' => 'borrowed',
        ]);
        Borrowing::factory()->create([
            'book_copy_id' => $copy->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/books/{$book->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }
}
```

---

## Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=BookTest

# Run specific test method
php artisan test --filter=test_book_belongs_to_category

# Run with coverage
php artisan test --coverage

# Run only unit tests
php artisan test tests/Unit

# Run only feature tests
php artisan test tests/Feature
```

---

## Test Database

Configure test database in `phpunit.xml`:

```xml
<php>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

Or use MySQL test database:

```xml
<php>
    <env name="DB_DATABASE" value="sukabaca_test"/>
</php>
```

---

## Test Helpers

### Traits

```php
use RefreshDatabase;  // Reset DB for each test
use WithFaker;        // Access faker instance
```

### Assertions

```php
$response->assertStatus(200);
$response->assertRedirect('/path');
$response->assertSee('Text');
$response->assertDontSee('Text');
$response->assertSessionHas('success');
$response->assertSessionHas('error');

$this->assertDatabaseHas('table', ['column' => 'value']);
$this->assertDatabaseMissing('table', ['column' => 'value']);
$this->assertDatabaseCount('table', 5);
```
