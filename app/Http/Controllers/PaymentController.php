<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $status = $request->query('status', 'all');

        $query = $user->payments()
            ->with(['borrowing.bookCopy.book']);

        // Apply Filter
        switch ($status) {
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'verified': // Using 'verified' to match design, assume 'confirmed' or 'verified' in DB
                $query->where('status', 'confirmed');
                break;
            case 'rejected':
                $query->where('status', 'rejected');
                break;
        }

        $payments = $query->latest()->paginate(10)->appends($request->query());

        // Calculate stats
        $counts = [
            'all' => $user->payments()->count(),
            'pending' => $user->payments()->where('status', 'pending')->count(),
            'verified' => $user->payments()->where('status', 'confirmed')->count(),
            'rejected' => $user->payments()->where('status', 'rejected')->count(),
        ];

        // Total Outstanding for the sidebar widget
        // Assuming this means unpaid borrowings total fee, similar to dashboard
        $totalOutstandingFees = $user->borrowings()
            ->where('is_paid', false)
            ->where('total_fee', '>', 0)
            ->sum('total_fee');

        return view('payments.index', [
            'payments' => $payments,
            'counts' => $counts,
            'currentStatus' => $status,
            'totalOutstandingFees' => $totalOutstandingFees,
        ]);
    }
}
