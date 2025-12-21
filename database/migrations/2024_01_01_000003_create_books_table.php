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
    Schema::create('books', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('author');
      $table->foreignId('category_id')
        ->constrained('categories')
        ->restrictOnDelete();
      $table->string('isbn', 20)->nullable()->unique();
      $table->text('description')->nullable();
      $table->unsignedInteger('total_copies')->default(0);
      $table->unsignedInteger('available_copies')->default(0);
      $table->unsignedInteger('times_borrowed')->default(0);
      $table->timestamps();

      // Indexes
      $table->index('title', 'idx_books_title');
      $table->index('available_copies', 'idx_books_available_copies');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('books');
  }
};
