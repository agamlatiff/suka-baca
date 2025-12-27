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
   * Generates 35+ borrowings with varied statuses spread over 3 months
   */
  public function run(): void
  {
    $users = User::where('role', 'user')->where('status', 'active')->get();

    if ($users->isEmpty()) {
      $this->command->warn('No active users found. Please run UserSeeder first.');
      return;
    }

    $lateFeePerDay = 2000;
    $borrowingIndex = 0;

    // Distribution: 40% active, 20% overdue, 30% returned, 10% pending
    $borrowings = [
      // ==================== ACTIVE BORROWINGS (14 records - 40%) ====================
      // Recent borrowings - still within due date
      ['user_index' => 0, 'book_title' => 'Laskar Pelangi', 'days_ago' => 3, 'duration' => 14, 'status' => 'active'],
      ['user_index' => 1, 'book_title' => 'Dilan 1990', 'days_ago' => 5, 'duration' => 14, 'status' => 'active'],
      ['user_index' => 2, 'book_title' => 'One Piece Vol. 1', 'days_ago' => 2, 'duration' => 7, 'status' => 'active'],
      ['user_index' => 3, 'book_title' => 'Atomic Habits', 'days_ago' => 4, 'duration' => 14, 'status' => 'active'],
      ['user_index' => 4, 'book_title' => 'Si Kancil dan Buaya', 'days_ago' => 1, 'duration' => 7, 'status' => 'active'],
      ['user_index' => 5, 'book_title' => 'Naruto Vol. 1', 'days_ago' => 6, 'duration' => 14, 'status' => 'active'],
      ['user_index' => 6, 'book_title' => 'Rich Dad Poor Dad', 'days_ago' => 3, 'duration' => 14, 'status' => 'active'],
      ['user_index' => 7, 'book_title' => 'Harry Potter dan Batu Bertuah', 'days_ago' => 5, 'duration' => 14, 'status' => 'active'],
      ['user_index' => 8, 'book_title' => 'Clean Code', 'days_ago' => 2, 'duration' => 14, 'status' => 'active'],
      ['user_index' => 9, 'book_title' => 'Perahu Kertas', 'days_ago' => 7, 'duration' => 14, 'status' => 'active'],
      ['user_index' => 10, 'book_title' => 'Sapiens: Sejarah Singkat Umat Manusia', 'days_ago' => 4, 'duration' => 14, 'status' => 'active'],
      ['user_index' => 11, 'book_title' => 'Attack on Titan Vol. 1', 'days_ago' => 1, 'duration' => 7, 'status' => 'active'],
      ['user_index' => 0, 'book_title' => 'Demon Slayer Vol. 1', 'days_ago' => 3, 'duration' => 7, 'status' => 'active'],
      ['user_index' => 1, 'book_title' => 'TOEFL Preparation Guide', 'days_ago' => 6, 'duration' => 14, 'status' => 'active'],

      // ==================== OVERDUE BORROWINGS (7 records - 20%) ====================
      ['user_index' => 12, 'book_title' => 'Bumi Manusia', 'days_ago' => 20, 'duration' => 14, 'status' => 'overdue'],
      ['user_index' => 13, 'book_title' => 'Tenggelamnya Kapal Van Der Wijck', 'days_ago' => 15, 'duration' => 7, 'status' => 'overdue'],
      ['user_index' => 14, 'book_title' => 'Dragon Ball Vol. 1', 'days_ago' => 12, 'duration' => 7, 'status' => 'overdue'],
      ['user_index' => 15, 'book_title' => 'Ayat-Ayat Cinta', 'days_ago' => 25, 'duration' => 14, 'status' => 'overdue'],
      ['user_index' => 16, 'book_title' => 'Negeri 5 Menara', 'days_ago' => 18, 'duration' => 14, 'status' => 'overdue'],
      ['user_index' => 2, 'book_title' => 'Fisika Dasar untuk Universitas', 'days_ago' => 10, 'duration' => 7, 'status' => 'overdue'],
      ['user_index' => 3, 'book_title' => 'The 7 Habits of Highly Effective People', 'days_ago' => 22, 'duration' => 14, 'status' => 'overdue'],

      // ==================== RETURNED BORROWINGS - PAID (11 records - 30%) ====================
      // Recent returns
      ['user_index' => 0, 'book_title' => 'Doraemon Vol. 1', 'days_ago' => 30, 'duration' => 7, 'returned_days_ago' => 25, 'status' => 'returned', 'is_paid' => true],
      ['user_index' => 1, 'book_title' => 'Siti Nurbaya', 'days_ago' => 35, 'duration' => 14, 'returned_days_ago' => 20, 'status' => 'returned', 'is_paid' => true],
      ['user_index' => 2, 'book_title' => 'Matematika SMA Kelas 12', 'days_ago' => 45, 'duration' => 14, 'returned_days_ago' => 30, 'status' => 'returned', 'is_paid' => true],
      ['user_index' => 3, 'book_title' => 'Hidup Sehat dengan Pola Makan', 'days_ago' => 40, 'duration' => 7, 'returned_days_ago' => 32, 'status' => 'returned', 'is_paid' => true],
      ['user_index' => 4, 'book_title' => 'Sejarah Indonesia Modern', 'days_ago' => 50, 'duration' => 14, 'returned_days_ago' => 35, 'status' => 'returned', 'is_paid' => true],
      ['user_index' => 5, 'book_title' => 'Petualangan Timun Mas', 'days_ago' => 55, 'duration' => 7, 'returned_days_ago' => 48, 'status' => 'returned', 'is_paid' => true],
      // Returns with late fees (paid)
      ['user_index' => 17, 'book_title' => 'Zero to One', 'days_ago' => 60, 'duration' => 14, 'returned_days_ago' => 40, 'status' => 'returned', 'is_paid' => true, 'days_late' => 6],
      ['user_index' => 18, 'book_title' => 'Mindset: The New Psychology of Success', 'days_ago' => 65, 'duration' => 14, 'returned_days_ago' => 48, 'status' => 'returned', 'is_paid' => true, 'days_late' => 3],
      ['user_index' => 6, 'book_title' => 'A Brief History of Time', 'days_ago' => 70, 'duration' => 14, 'returned_days_ago' => 52, 'status' => 'returned', 'is_paid' => true, 'days_late' => 4],
      ['user_index' => 7, 'book_title' => 'The Psychology of Money', 'days_ago' => 75, 'duration' => 14, 'returned_days_ago' => 58, 'status' => 'returned', 'is_paid' => true, 'days_late' => 3],
      ['user_index' => 8, 'book_title' => 'Deep Work', 'days_ago' => 80, 'duration' => 14, 'returned_days_ago' => 65, 'status' => 'returned', 'is_paid' => true, 'days_late' => 1],

      // ==================== PENDING BORROWINGS (3 records - 10%) ====================
      ['user_index' => 19, 'book_title' => 'The Pragmatic Programmer', 'days_ago' => 1, 'duration' => 14, 'status' => 'pending'],
      ['user_index' => 20, 'book_title' => 'Tafsir Al-Quran Al-Azhar Jilid 1', 'days_ago' => 0, 'duration' => 14, 'status' => 'pending'],
      ['user_index' => 21, 'book_title' => 'Why We Sleep', 'days_ago' => 1, 'duration' => 7, 'status' => 'pending'],
    ];

    foreach ($borrowings as $index => $data) {
      $user = $users[$data['user_index'] % $users->count()];
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

      // Calculate late days for overdue that's not returned
      if ($data['status'] === 'overdue' && !$returnedAt) {
        $daysLate = max(0, Carbon::today()->diffInDays($dueDate, false) * -1);
      }

      $lateFee = $daysLate * $lateFeePerDay;
      $totalFee = $book->rental_fee + $lateFee;

      // Create borrowing
      $borrowing = Borrowing::create([
        'borrowing_code' => sprintf('BRW-%s-%03d', $borrowedAt->format('Ymd'), $borrowingIndex + 1),
        'user_id' => $user->id,
        'book_copy_id' => $copy->id,
        'borrowed_at' => $borrowedAt,
        'due_date' => $dueDate,
        'returned_at' => $returnedAt,
        'rental_fee' => $book->rental_fee,
        'late_fee' => $lateFee,
        'total_fee' => $data['status'] === 'returned' ? $totalFee : $book->rental_fee,
        'is_paid' => $data['is_paid'] ?? false,
        'status' => $data['status'],
        'days_late' => $daysLate,
      ]);

      // Update copy status
      if ($data['status'] !== 'returned' && $data['status'] !== 'pending') {
        $copy->update(['status' => 'borrowed']);
        $book->decrement('available_copies');
      } elseif ($data['status'] === 'returned') {
        $book->increment('times_borrowed');
      }

      $borrowingIndex++;
    }
  }
}
