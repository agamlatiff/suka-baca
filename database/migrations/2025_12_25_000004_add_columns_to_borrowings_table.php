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
    Schema::table('borrowings', function (Blueprint $table) {
      if (!Schema::hasColumn('borrowings', 'damage_fee')) {
        $table->decimal('damage_fee', 10, 2)->default(0)->after('late_fee');
      }
      if (!Schema::hasColumn('borrowings', 'return_condition')) {
        $table->enum('return_condition', ['good', 'damaged', 'lost'])->nullable()->after('status');
      }
      if (!Schema::hasColumn('borrowings', 'is_extended')) {
        $table->boolean('is_extended')->default(false)->after('days_late');
      }
      if (!Schema::hasColumn('borrowings', 'extension_date')) {
        $table->date('extension_date')->nullable()->after('is_extended');
      }
      if (!Schema::hasColumn('borrowings', 'notes')) {
        $table->text('notes')->nullable()->after('extension_date');
      }
    });

    // Change status enum to include pending and rejected
    DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('pending', 'active', 'returned', 'overdue', 'rejected') DEFAULT 'pending'");
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('borrowings', function (Blueprint $table) {
      $columns = ['damage_fee', 'return_condition', 'is_extended', 'extension_date', 'notes'];
      foreach ($columns as $column) {
        if (Schema::hasColumn('borrowings', $column)) {
          $table->dropColumn($column);
        }
      }
    });

    DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('active', 'returned', 'overdue') DEFAULT 'active'");
  }
};
