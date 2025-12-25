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
        'value' => '1000',
        'description' => 'Denda keterlambatan per hari (Rp)',
      ],
      [
        'key' => 'rental_duration_days',
        'value' => '7',
        'description' => 'Durasi peminjaman standar (hari)',
      ],
      [
        'key' => 'max_books_per_user',
        'value' => '3',
        'description' => 'Maksimal buku yang bisa dipinjam per user',
      ],
      [
        'key' => 'bank_name',
        'value' => 'BCA',
        'description' => 'Nama Bank Pembayaran',
      ],
      [
        'key' => 'account_number',
        'value' => '1234567890',
        'description' => 'Nomor Rekening',
      ],
      [
        'key' => 'account_name',
        'value' => 'Perpustakaan Suka Baca',
        'description' => 'Nama Pemilik Rekening',
      ],
      [
        'key' => 'damage_fee_percentage',
        'value' => '50',
        'description' => 'Denda kerusakan (% dari harga buku)',
      ],
    ];

    foreach ($settings as $setting) {
      Setting::updateOrCreate(
        ['key' => $setting['key']],
        $setting
      );
    }
  }
}
