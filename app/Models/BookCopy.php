<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookCopy extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'book_id',
    'copy_code',
    'status',
    'notes',
  ];

  /**
   * Get the book that owns the copy.
   */
  public function book(): BelongsTo
  {
    return $this->belongsTo(Book::class);
  }

  /**
   * Get the borrowings for this copy.
   */
  public function borrowings(): HasMany
  {
    return $this->hasMany(Borrowing::class);
  }

  /**
   * Check if the copy is available.
   */
  public function isAvailable(): bool
  {
    return $this->status === 'available';
  }

  /**
   * Scope to get only available copies.
   */
  public function scopeAvailable($query)
  {
    return $query->where('status', 'available');
  }

  /**
   * Scope to get only borrowed copies.
   */
  public function scopeBorrowed($query)
  {
    return $query->where('status', 'borrowed');
  }

  /**
   * Mark copy as borrowed.
   */
  public function markAsBorrowed(): bool
  {
    return $this->update(['status' => 'borrowed']);
  }

  /**
   * Mark copy as available.
   */
  public function markAsAvailable(): bool
  {
    return $this->update(['status' => 'available']);
  }
}
