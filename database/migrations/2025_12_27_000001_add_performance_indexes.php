<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   * 
   * Performance optimization: Add indexes to frequently queried columns
   */
  public function up(): void
  {
    // Indexes for borrowings table
    Schema::table('borrowings', function (Blueprint $table) {
      // Index for user lookups and status filtering
      $table->index(['user_id', 'status'], 'borrowings_user_status_idx');

      // Index for due date queries (overdue checking)
      $table->index(['status', 'due_date'], 'borrowings_status_due_idx');

      // Index for date range queries
      $table->index('borrowed_at', 'borrowings_borrowed_at_idx');
    });

    // Indexes for payments table
    Schema::table('payments', function (Blueprint $table) {
      // Index for status filtering
      $table->index('status', 'payments_status_idx');

      // Index for verified payments queries
      $table->index(['status', 'verified_at'], 'payments_status_verified_idx');
    });

    // Index for books table
    Schema::table('books', function (Blueprint $table) {
      // Index for popularity queries
      $table->index('times_borrowed', 'books_popularity_idx');

      // Index for availability queries
      $table->index('available_copies', 'books_availability_idx');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('borrowings', function (Blueprint $table) {
      $table->dropIndex('borrowings_user_status_idx');
      $table->dropIndex('borrowings_status_due_idx');
      $table->dropIndex('borrowings_borrowed_at_idx');
    });

    Schema::table('payments', function (Blueprint $table) {
      $table->dropIndex('payments_status_idx');
      $table->dropIndex('payments_status_verified_idx');
    });

    Schema::table('books', function (Blueprint $table) {
      $table->dropIndex('books_popularity_idx');
      $table->dropIndex('books_availability_idx');
    });
  }
};
