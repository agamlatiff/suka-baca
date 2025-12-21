<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('settings', function (Blueprint $table) {
      $table->id();
      $table->string('key', 100)->unique();
      $table->text('value');
      $table->string('description', 255)->nullable();
      $table->timestamps();
    });

    // Insert default settings
    DB::table('settings')->insert([
      [
        'key' => 'late_fee_per_day',
        'value' => '2000',
        'description' => 'Late penalty per day (Rp)',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'key' => 'max_borrow_days',
        'value' => '14',
        'description' => 'Maximum borrowing duration (days)',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'key' => 'max_books_per_user',
        'value' => '3',
        'description' => 'Maximum books per user at once',
        'created_at' => now(),
        'updated_at' => now(),
      ],
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('settings');
  }
};
