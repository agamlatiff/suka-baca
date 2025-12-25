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
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->string('payment_code', 50)->unique();
      $table->foreignId('user_id')
        ->constrained('users')
        ->restrictOnDelete();
      $table->foreignId('borrowing_id')
        ->constrained('borrowings')
        ->restrictOnDelete();
      $table->enum('type', ['rental', 'extension', 'late_fee', 'damage_fee']);
      $table->decimal('amount', 10, 2);
      $table->enum('method', ['cash', 'transfer']);
      $table->string('proof_image')->nullable();
      $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
      $table->timestamp('verified_at')->nullable();
      $table->foreignId('verified_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();
      $table->text('rejection_notes')->nullable();
      $table->timestamps();

      // Indexes
      $table->index('user_id', 'idx_payments_user');
      $table->index('borrowing_id', 'idx_payments_borrowing');
      $table->index('status', 'idx_payments_status');
      $table->index('type', 'idx_payments_type');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('payments');
  }
};
