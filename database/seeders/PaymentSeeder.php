<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   * Generates payments for borrowings with varied statuses
   */
  public function run(): void
  {
    // Get all borrowings that need payments
    $borrowings = Borrowing::with('user')->get();

    $paymentIndex = 0;

    foreach ($borrowings as $borrowing) {
      // Skip if payment already exists
      if (Payment::where('borrowing_id', $borrowing->id)->exists()) {
        continue;
      }

      $paymentStatus = 'pending';
      $verifiedAt = null;
      $paymentMethod = collect(['transfer', 'cash'])->random();

      // Determine payment status based on borrowing status
      if ($borrowing->is_paid) {
        $paymentStatus = 'verified';
        $verifiedAt = $borrowing->returned_at ?? Carbon::now()->subDays(rand(1, 10));
      } elseif ($borrowing->status === 'pending') {
        // Pending borrowings have pending payments
        $paymentStatus = 'pending';
      } elseif ($borrowing->status === 'active' || $borrowing->status === 'overdue') {
        // Active/overdue borrowings - mix of payment statuses
        $rand = rand(1, 100);
        if ($rand <= 60) {
          $paymentStatus = 'verified';
          $verifiedAt = $borrowing->borrowed_at->copy()->addDays(rand(1, 3));
        } elseif ($rand <= 85) {
          $paymentStatus = 'pending';
        } else {
          $paymentStatus = 'rejected';
        }
      } elseif ($borrowing->status === 'returned') {
        // Returned borrowings should have verified payments
        $paymentStatus = 'verified';
        $verifiedAt = $borrowing->returned_at->copy()->subDays(rand(0, 5));
      }

      // Calculate amount (rental fee + late fee if applicable)
      $amount = $borrowing->rental_fee;
      if ($borrowing->status === 'returned' && $borrowing->late_fee > 0) {
        $amount = $borrowing->total_fee;
      }

      // Create payment
      Payment::create([
        'payment_code' => sprintf('PAY-%s-%03d', Carbon::now()->format('Ymd'), $paymentIndex + 1),
        'borrowing_id' => $borrowing->id,
        'user_id' => $borrowing->user_id,
        'amount' => $amount,
        'type' => $borrowing->late_fee > 0 ? 'late_fee' : 'rental',
        'method' => $paymentMethod,
        'proof_image' => $paymentMethod === 'transfer' ? 'payments/proof-sample.jpg' : null,
        'status' => $paymentStatus,
        'verified_at' => $verifiedAt,
        'rejection_notes' => $paymentStatus === 'rejected' ? 'Bukti pembayaran tidak valid' : null,
        'created_at' => $borrowing->borrowed_at,
        'updated_at' => $verifiedAt ?? Carbon::now(),
      ]);

      $paymentIndex++;
    }

    // Add some additional late fee payments for returned books with fines
    $returnedWithFines = Borrowing::where('status', 'returned')
      ->where('late_fee', '>', 0)
      ->get();

    foreach ($returnedWithFines as $borrowing) {
      // Check if late fee payment already exists
      $existingFinePayment = Payment::where('borrowing_id', $borrowing->id)
        ->where('type', 'late_fee')
        ->exists();

      if (!$existingFinePayment && $borrowing->late_fee > 0) {
        Payment::create([
          'payment_code' => sprintf('PAY-FINE-%s-%03d', Carbon::now()->format('Ymd'), $paymentIndex + 1),
          'borrowing_id' => $borrowing->id,
          'user_id' => $borrowing->user_id,
          'amount' => $borrowing->late_fee,
          'type' => 'late_fee',
          'method' => collect(['transfer', 'cash'])->random(),
          'proof_image' => null,
          'status' => 'verified',
          'verified_at' => $borrowing->returned_at,
          'rejection_notes' => 'Denda keterlambatan ' . $borrowing->days_late . ' hari',
          'created_at' => $borrowing->returned_at,
          'updated_at' => $borrowing->returned_at,
        ]);
        $paymentIndex++;
      }
    }
  }
}
