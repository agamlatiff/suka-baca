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
    Schema::create('book_copies', function (Blueprint $table) {
      $table->id();
      $table->foreignId('book_id')
        ->constrained('books')
        ->cascadeOnDelete();
      $table->string('copy_code', 50)->unique();
      $table->enum('status', ['available', 'borrowed', 'maintenance', 'lost'])
        ->default('available');
      $table->text('notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('status', 'idx_book_copies_status');
      $table->index(['book_id', 'status'], 'idx_book_copies_book_status');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('book_copies');
  }
};
