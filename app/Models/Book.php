<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'title',
    'slug',
    'author',
    'publisher',
    'year',
    'category_id',
    'isbn',
    'image',
    'description',
    'rental_fee',
    'book_price',
    'total_copies',
    'available_copies',
    'times_borrowed',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'rental_fee' => 'decimal:2',
    'book_price' => 'decimal:2',
    'year' => 'integer',
    'total_copies' => 'integer',
    'available_copies' => 'integer',
    'times_borrowed' => 'integer',
  ];

  /**
   * Get the category that owns the book.
   */
  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }

  /**
   * Get the copies for the book.
   */
  public function copies(): HasMany
  {
    return $this->hasMany(BookCopy::class);
  }

  /**
   * Check if the book is available for borrowing.
   */
  public function isAvailable(): bool
  {
    return $this->available_copies > 0;
  }

  /**
   * Scope to get only available books.
   */
  public function scopeAvailable($query)
  {
    return $query->where('available_copies', '>', 0);
  }

  /**
   * Get the wishlists for this book.
   */
  public function wishlists(): HasMany
  {
    return $this->hasMany(Wishlist::class);
  }

  /**
   * Get cover image URL.
   */
  public function getImageUrlAttribute(): ?string
  {
    return $this->image ? asset('storage/' . $this->image) : null;
  }
}
