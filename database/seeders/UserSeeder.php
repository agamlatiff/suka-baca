<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   * Generates 25+ user members with varied statuses
   */
  public function run(): void
  {
    $users = [
      // Active users with complete profiles
      [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'phone' => '081234567890',
        'status' => 'active',
      ],
      [
        'name' => 'Siti Rahayu',
        'email' => 'siti@example.com',
        'phone' => '081234567891',
        'status' => 'active',
      ],
      [
        'name' => 'Ahmad Hidayat',
        'email' => 'ahmad@example.com',
        'phone' => '081234567892',
        'status' => 'active',
      ],
      [
        'name' => 'Dewi Lestari',
        'email' => 'dewi@example.com',
        'phone' => '081234567893',
        'status' => 'active',
      ],
      [
        'name' => 'Eko Prasetyo',
        'email' => 'eko@example.com',
        'phone' => '081234567894',
        'status' => 'active',
      ],
      [
        'name' => 'Fitri Handayani',
        'email' => 'fitri@example.com',
        'phone' => '081234567895',
        'status' => 'active',
      ],
      [
        'name' => 'Gunawan Wijaya',
        'email' => 'gunawan@example.com',
        'phone' => '081234567896',
        'status' => 'active',
      ],
      [
        'name' => 'Hana Permata',
        'email' => 'hana@example.com',
        'phone' => '081234567897',
        'status' => 'active',
      ],
      [
        'name' => 'Irfan Maulana',
        'email' => 'irfan@example.com',
        'phone' => '081234567898',
        'status' => 'active',
      ],
      [
        'name' => 'Julia Anggraini',
        'email' => 'julia@example.com',
        'phone' => '081234567899',
        'status' => 'active',
      ],
      // More active users
      [
        'name' => 'Krisna Putra',
        'email' => 'krisna@example.com',
        'phone' => '082111222333',
        'status' => 'active',
      ],
      [
        'name' => 'Linda Sari',
        'email' => 'linda@example.com',
        'phone' => '082111222334',
        'status' => 'active',
      ],
      [
        'name' => 'Muhamad Rizki',
        'email' => 'rizki@example.com',
        'phone' => '082111222335',
        'status' => 'active',
      ],
      [
        'name' => 'Nadia Putri',
        'email' => 'nadia@example.com',
        'phone' => '082111222336',
        'status' => 'active',
      ],
      [
        'name' => 'Oscar Pratama',
        'email' => 'oscar@example.com',
        'phone' => '082111222337',
        'status' => 'active',
      ],
      [
        'name' => 'Putri Ayu',
        'email' => 'putriayu@example.com',
        'phone' => '082111222338',
        'status' => 'active',
      ],
      [
        'name' => 'Qori Ramadhan',
        'email' => 'qori@example.com',
        'phone' => '082111222339',
        'status' => 'active',
      ],
      [
        'name' => 'Rina Wati',
        'email' => 'rina@example.com',
        'phone' => '082111222340',
        'status' => 'active',
      ],
      [
        'name' => 'Surya Darma',
        'email' => 'surya@example.com',
        'phone' => '082111222341',
        'status' => 'active',
      ],
      [
        'name' => 'Tania Dewi',
        'email' => 'tania@example.com',
        'phone' => '082111222342',
        'status' => 'active',
      ],
      [
        'name' => 'Udin Sedunia',
        'email' => 'udin@example.com',
        'phone' => '082111222343',
        'status' => 'active',
      ],
      [
        'name' => 'Vina Panduwinata',
        'email' => 'vina@example.com',
        'phone' => '082111222344',
        'status' => 'active',
      ],
      [
        'name' => 'Wawan Setiawan',
        'email' => 'wawan@example.com',
        'phone' => '082111222345',
        'status' => 'active',
      ],
      // Suspended users (for testing)
      [
        'name' => 'Xander Cage',
        'email' => 'xander@example.com',
        'phone' => '082999888777',
        'status' => 'suspended',
      ],
      [
        'name' => 'Yanto Subroto',
        'email' => 'yanto@example.com',
        'phone' => '082999888778',
        'status' => 'suspended',
      ],
      [
        'name' => 'Zainab Fatimah',
        'email' => 'zainab@example.com',
        'phone' => '082999888779',
        'status' => 'suspended',
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
          'status' => $userData['status'] ?? 'active',
        ]
      );
    }
  }
}
