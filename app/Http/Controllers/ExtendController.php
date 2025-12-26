<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtendController extends Controller
{
  /**
   * Show the extension wizard for a specific borrowing.
   */
  public function create(Borrowing $borrowing)
  {
    // Ensure user owns this borrowing
    if ($borrowing->user_id !== Auth::id()) {
      abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
    }

    // Check if borrowing is still active
    if ($borrowing->status !== 'active') {
      return redirect()->route('borrowings.index')
        ->with('error', 'Peminjaman ini tidak dapat diperpanjang.');
    }

    // Check if already extended max times
    $maxExtensions = (int) \App\Models\Setting::get('max_extensions', 2);
    if ($borrowing->extension_count >= $maxExtensions) {
      return redirect()->route('borrowings.index')
        ->with('error', "Sudah mencapai batas maksimal perpanjangan ($maxExtensions kali).");
    }

    return view('extend.create', compact('borrowing'));
  }
}
