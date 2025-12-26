<?php

namespace App\Services;

use App\Models\Wishlist;
use Illuminate\Pagination\LengthAwarePaginator;

class WishlistService
{
  /**
   * Get user's wishlist items paginated.
   */
  public function getUserWishlists(int $userId, int $perPage = 12): LengthAwarePaginator
  {
    return Wishlist::where('user_id', $userId)
      ->with('book.category')
      ->latest()
      ->paginate($perPage);
  }

  /**
   * Add a book to wishlist.
   */
  public function addToWishlist(int $userId, int $bookId): array
  {
    // Check if already in wishlist
    $exists = Wishlist::where('user_id', $userId)
      ->where('book_id', $bookId)
      ->exists();

    if ($exists) {
      return [
        'success' => false,
        'message' => 'Buku ini sudah ada di wishlist Anda.',
      ];
    }

    Wishlist::create([
      'user_id' => $userId,
      'book_id' => $bookId,
    ]);

    return [
      'success' => true,
      'message' => 'Buku berhasil ditambahkan ke wishlist.',
    ];
  }

  /**
   * Remove a book from wishlist.
   */
  public function removeFromWishlist(int $userId, int $wishlistId): array
  {
    $wishlist = Wishlist::where('user_id', $userId)
      ->where('id', $wishlistId)
      ->first();

    if (!$wishlist) {
      return [
        'success' => false,
        'message' => 'Item wishlist tidak ditemukan.',
      ];
    }

    $wishlist->delete();

    return [
      'success' => true,
      'message' => 'Buku berhasil dihapus dari wishlist.',
    ];
  }

  /**
   * Check if book is in user's wishlist.
   */
  public function isInWishlist(int $userId, int $bookId): bool
  {
    return Wishlist::where('user_id', $userId)
      ->where('book_id', $bookId)
      ->exists();
  }
}
