<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $users = [
      [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'phone' => '081234567890',
      ],
      [
        'name' => 'Siti Rahayu',
        'email' => 'siti@example.com',
        'phone' => '081234567891',
      ],
      [
        'name' => 'Ahmad Hidayat',
        'email' => 'ahmad@example.com',
        'phone' => '081234567892',
      ],
      [
        'name' => 'Dewi Lestari',
        'email' => 'dewi@example.com',
        'phone' => '081234567893',
      ],
      [
        'name' => 'Eko Prasetyo',
        'email' => 'eko@example.com',
        'phone' => '081234567894',
      ],
      [
        'name' => 'Fitri Handayani',
        'email' => 'fitri@example.com',
        'phone' => '081234567895',
      ],
      [
        'name' => 'Gunawan Wijaya',
        'email' => 'gunawan@example.com',
        'phone' => '081234567896',
      ],
      [
        'name' => 'Hana Permata',
        'email' => 'hana@example.com',
        'phone' => '081234567897',
      ],
      [
        'name' => 'Irfan Maulana',
        'email' => 'irfan@example.com',
        'phone' => '081234567898',
      ],
      [
        'name' => 'Julia Anggraini',
        'email' => 'julia@example.com',
        'phone' => '081234567899',
      ],
    ];

    foreach ($users as $userData) {
      User::firstOrCreate(
        ['email' => $userData['email']],
        [
          'name' => $userData['name'],
          'password' => Hash::make('password'),
          'role' => 'user',
          'phone' => $userData['phone'],
        ]
      );
    }
  }
}
