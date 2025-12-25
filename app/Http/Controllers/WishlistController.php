<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index(): View
    {
        $wishlists = Auth::user()->wishlists()->with('book.category')->latest()->paginate(12);

        return view('wishlist.index', [
            'wishlists' => $wishlists,
        ]);
    }

    /**
     * Add a book to the wishlist.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = Auth::user();

        // Check if already in wishlist
        if ($user->wishlists()->where('book_id', $request->book_id)->exists()) {
            return back()->with('info', 'Buku ini sudah ada di wishlist Anda.');
        }

        $user->wishlists()->create([
            'book_id' => $request->book_id,
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan ke wishlist.');
    }

    /**
     * Remove a book from the wishlist.
     */
    public function destroy(string $id)
    {
        $wishlist = Auth::user()->wishlists()->findOrFail($id);
        $wishlist->delete();

        return back()->with('success', 'Buku berhasil dihapus dari wishlist.');
    }
}
