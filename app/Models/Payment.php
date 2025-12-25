<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Payment extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'payment_code',
    'user_id',
    'borrowing_id',
    'type',
    'amount',
    'method',
    'proof_image',
    'status',
    'verified_at',
    'verified_by',
    'rejection_notes',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'amount' => 'decimal:2',
    'verified_at' => 'datetime',
  ];

  /**
   * Get the user that made the payment.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the borrowing associated with this payment.
   */
  public function borrowing(): BelongsTo
  {
    return $this->belongsTo(Borrowing::class);
  }

  /**
   * Get the admin who verified this payment.
   */
  public function verifier(): BelongsTo
  {
    return $this->belongsTo(User::class, 'verified_by');
  }

  /**
   * Scope to get only pending payments.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope to get only verified payments.
   */
  public function scopeVerified($query)
  {
    return $query->where('status', 'verified');
  }

  /**
   * Check if payment is pending.
   */
  public function isPending(): bool
  {
    return $this->status === 'pending';
  }

  /**
   * Verify this payment.
   */
  public function verify(int $adminId): bool
  {
    return $this->update([
      'status' => 'verified',
      'verified_at' => Carbon::now(),
      'verified_by' => $adminId,
    ]);
  }

  /**
   * Reject this payment.
   */
  public function reject(int $adminId, string $notes): bool
  {
    return $this->update([
      'status' => 'rejected',
      'verified_at' => Carbon::now(),
      'verified_by' => $adminId,
      'rejection_notes' => $notes,
    ]);
  }

  /**
   * Get proof image URL.
   */
  public function getProofImageUrlAttribute(): ?string
  {
    return $this->proof_image ? asset('storage/' . $this->proof_image) : null;
  }

  /**
   * Generate a unique payment code.
   */
  public static function generatePaymentCode(): string
  {
    $date = Carbon::today()->format('Ymd');
    $count = static::whereDate('created_at', Carbon::today())->count() + 1;
    return sprintf('PAY-%s-%03d', $date, $count);
  }
}
