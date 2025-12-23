<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $users = User::where('role', 'user')->get();

    if ($users->isEmpty()) {
      $this->command->warn('No users found. Please run UserSeeder first.');
      return;
    }

    $borrowings = [
      // Active borrowings (still on time)
      [
        'user_index' => 0,
        'book_title' => 'Laskar Pelangi',
        'days_ago' => 5,
        'duration' => 14,
        'status' => 'active',
      ],
      [
        'user_index' => 1,
        'book_title' => 'Bumi Manusia',
        'days_ago' => 3,
        'duration' => 7,
        'status' => 'active',
      ],
      [
        'user_index' => 2,
        'book_title' => 'One Piece Vol. 1',
        'days_ago' => 2,
        'duration' => 7,
        'status' => 'active',
      ],
      [
        'user_index' => 3,
        'book_title' => 'Atomic Habits',
        'days_ago' => 1,
        'duration' => 14,
        'status' => 'active',
      ],
      [
        'user_index' => 4,
        'book_title' => 'Si Kancil dan Buaya',
        'days_ago' => 4,
        'duration' => 7,
        'status' => 'active',
      ],
      // Overdue borrowings
      [
        'user_index' => 5,
        'book_title' => 'Rich Dad Poor Dad',
        'days_ago' => 10,
        'duration' => 7,
        'status' => 'overdue',
      ],
      [
        'user_index' => 6,
        'book_title' => 'Sapiens: Sejarah Singkat Umat Manusia',
        'days_ago' => 20,
        'duration' => 14,
        'status' => 'overdue',
      ],
      // Returned borrowings (paid)
      [
        'user_index' => 0,
        'book_title' => 'Naruto Vol. 1',
        'days_ago' => 30,
        'duration' => 7,
        'returned_days_ago' => 20,
        'status' => 'returned',
        'is_paid' => true,
      ],
      [
        'user_index' => 1,
        'book_title' => 'Siti Nurbaya',
        'days_ago' => 25,
        'duration' => 14,
        'returned_days_ago' => 15,
        'status' => 'returned',
        'is_paid' => true,
      ],
      [
        'user_index' => 2,
        'book_title' => 'Matematika SMA Kelas 12',
        'days_ago' => 40,
        'duration' => 14,
        'returned_days_ago' => 25,
        'status' => 'returned',
        'is_paid' => true,
      ],
      [
        'user_index' => 3,
        'book_title' => 'Hidup Sehat dengan Pola Makan',
        'days_ago' => 35,
        'duration' => 7,
        'returned_days_ago' => 30,
        'status' => 'returned',
        'is_paid' => true,
      ],
      // Returned with late fee (unpaid)
      [
        'user_index' => 7,
        'book_title' => 'Tenggelamnya Kapal Van Der Wijck',
        'days_ago' => 20,
        'duration' => 7,
        'returned_days_ago' => 5,
        'status' => 'returned',
        'is_paid' => false,
        'days_late' => 8,
      ],
      // Returned (paid with late fee)
      [
        'user_index' => 8,
        'book_title' => 'Sejarah Indonesia Modern',
        'days_ago' => 45,
        'duration' => 14,
        'returned_days_ago' => 28,
        'status' => 'returned',
        'is_paid' => true,
        'days_late' => 3,
      ],
      // More active
      [
        'user_index' => 9,
        'book_title' => 'Petualangan Timun Mas',
        'days_ago' => 6,
        'duration' => 14,
        'status' => 'active',
      ],
      [
        'user_index' => 4,
        'book_title' => 'Bahasa Indonesia untuk Pemula',
        'days_ago' => 8,
        'duration' => 14,
        'status' => 'active',
      ],
    ];

    $lateFeePerDay = 2000;

    foreach ($borrowings as $index => $data) {
      $user = $users[$data['user_index']] ?? $users->first();
      $book = Book::where('title', $data['book_title'])->first();

      if (!$book) {
        continue;
      }

      // Find available copy
      $copy = BookCopy::where('book_id', $book->id)
        ->where('status', 'available')
        ->first();

      if (!$copy) {
        continue;
      }

      $borrowedAt = Carbon::today()->subDays($data['days_ago']);
      $dueDate = $borrowedAt->copy()->addDays($data['duration']);
      $returnedAt = isset($data['returned_days_ago'])
        ? Carbon::today()->subDays($data['returned_days_ago'])
        : null;

      $daysLate = $data['days_late'] ?? 0;
      $lateFee = $daysLate * $lateFeePerDay;
      $totalFee = $book->rental_fee + $lateFee;

      // Calculate overdue status
      $status = $data['status'];
      if ($status === 'active' && Carbon::today()->gt($dueDate)) {
        $status = 'overdue';
      }

      // Create borrowing
      $borrowing = Borrowing::create([
        'borrowing_code' => sprintf('BRW-%s-%03d', $borrowedAt->format('Ymd'), $index + 1),
        'user_id' => $user->id,
        'book_copy_id' => $copy->id,
        'borrowed_at' => $borrowedAt,
        'due_date' => $dueDate,
        'returned_at' => $returnedAt,
        'rental_fee' => $book->rental_fee,
        'late_fee' => $lateFee,
        'total_fee' => $status === 'returned' ? $totalFee : $book->rental_fee,
        'is_paid' => $data['is_paid'] ?? false,
        'status' => $status,
        'days_late' => $daysLate,
      ]);

      // Update copy status
      if ($status !== 'returned') {
        $copy->update(['status' => 'borrowed']);
        $book->decrement('available_copies');
      } else {
        $book->increment('times_borrowed');
      }
    }
  }
}
