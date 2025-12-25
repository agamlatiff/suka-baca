<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('books', function (Blueprint $table) {
      if (!Schema::hasColumn('books', 'slug')) {
        $table->string('slug')->unique()->after('title');
      }
      if (!Schema::hasColumn('books', 'publisher')) {
        $table->string('publisher')->nullable()->after('author');
      }
      if (!Schema::hasColumn('books', 'year')) {
        $table->smallInteger('year')->nullable()->after('publisher');
      }
      if (!Schema::hasColumn('books', 'isbn')) {
        $table->string('isbn', 20)->nullable()->unique()->after('category_id');
      }
      if (!Schema::hasColumn('books', 'image')) {
        $table->string('image')->nullable()->after('isbn');
      }
      if (!Schema::hasColumn('books', 'rental_fee')) {
        $table->decimal('rental_fee', 10, 2)->default(0)->after('description');
      }
      if (!Schema::hasColumn('books', 'book_price')) {
        $table->decimal('book_price', 10, 2)->nullable()->after('rental_fee');
      }
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('books', function (Blueprint $table) {
      $columns = ['slug', 'publisher', 'year', 'isbn', 'image', 'rental_fee', 'book_price'];
      foreach ($columns as $column) {
        if (Schema::hasColumn('books', $column)) {
          $table->dropColumn($column);
        }
      }
    });
  }
};
