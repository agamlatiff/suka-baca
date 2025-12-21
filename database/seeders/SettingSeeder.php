<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $settings = [
      [
        'key' => 'late_fee_per_day',
        'value' => '2000',
        'description' => 'Denda keterlambatan per hari (dalam Rupiah)',
      ],
      [
        'key' => 'max_borrow_days',
        'value' => '14',
        'description' => 'Maksimal durasi peminjaman (dalam hari)',
      ],
      [
        'key' => 'max_books_per_user',
        'value' => '3',
        'description' => 'Maksimal buku yang bisa dipinjam sekaligus per user',
      ],
      [
        'key' => 'default_rental_fee',
        'value' => '5000',
        'description' => 'Biaya sewa default untuk buku baru (dalam Rupiah)',
      ],
      [
        'key' => 'library_name',
        'value' => 'Perpustakaan Sukabaca',
        'description' => 'Nama perpustakaan',
      ],
      [
        'key' => 'library_address',
        'value' => 'Jl. Contoh No. 123, Jakarta',
        'description' => 'Alamat perpustakaan',
      ],
    ];

    foreach ($settings as $setting) {
      Setting::firstOrCreate(
        ['key' => $setting['key']],
        $setting
      );
    }
  }
}
