<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToWishlistRequest;
use App\Services\WishlistService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistService $wishlistService
    ) {}

    /**
     * Display the user's wishlist.
     */
    public function index(): View
    {
        $wishlists = $this->wishlistService->getUserWishlists(Auth::id(), 12);

        return view('wishlist.index', [
            'wishlists' => $wishlists,
        ]);
    }

    /**
     * Add a book to the wishlist.
     */
    public function store(AddToWishlistRequest $request)
    {
        $result = $this->wishlistService->addToWishlist(Auth::id(), $request->book_id);

        $type = $result['success'] ? 'success' : 'info';
        return back()->with($type, $result['message']);
    }

    /**
     * Remove a book from the wishlist.
     */
    public function destroy(string $id)
    {
        $result = $this->wishlistService->removeFromWishlist(Auth::id(), (int) $id);

        $type = $result['success'] ? 'success' : 'error';
        return back()->with($type, $result['message']);
    }
}
