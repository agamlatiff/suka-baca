<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $status = $request->query('status', 'all');

        $payments = $this->paymentService->getUserPaymentsFiltered($userId, $status, 10);
        $payments->appends($request->query());

        $counts = $this->paymentService->getPaymentCounts($userId);
        $totalOutstandingFees = $this->paymentService->getTotalOutstandingFees($userId);

        return view('payments.index', [
            'payments' => $payments,
            'counts' => $counts,
            'currentStatus' => $status,
            'totalOutstandingFees' => $totalOutstandingFees,
        ]);
    }
}
