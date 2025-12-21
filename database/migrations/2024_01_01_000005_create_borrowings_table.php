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
    Schema::create('borrowings', function (Blueprint $table) {
      $table->id();
      $table->string('borrowing_code', 50)->unique();
      $table->foreignId('user_id')
        ->constrained('users')
        ->restrictOnDelete();
      $table->foreignId('book_copy_id')
        ->constrained('book_copies')
        ->restrictOnDelete();
      $table->date('borrowed_at');
      $table->date('due_date');
      $table->date('returned_at')->nullable();
      $table->decimal('rental_fee', 10, 2)->default(0.00);
      $table->decimal('late_fee', 10, 2)->default(0.00);
      $table->decimal('total_fee', 10, 2)->default(0.00);
      $table->boolean('is_paid')->default(false);
      $table->enum('status', ['active', 'returned', 'overdue'])->default('active');
      $table->integer('days_late')->default(0);
      $table->timestamps();

      // Indexes
      $table->index('status', 'idx_borrowings_status');
      $table->index('due_date', 'idx_borrowings_due_date');
      $table->index('is_paid', 'idx_borrowings_is_paid');
      $table->index(['user_id', 'status'], 'idx_borrowings_user_status');
      $table->index(['status', 'due_date'], 'idx_borrowings_status_duedate');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('borrowings');
  }
};
