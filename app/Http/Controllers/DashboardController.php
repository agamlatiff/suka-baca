<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
  public function __construct(
    protected DashboardService $dashboardService
  ) {}

  /**
   * Display the user dashboard.
   */
  public function index(): View
  {
    $data = $this->dashboardService->getUserDashboardData(Auth::id());

    return view('dashboard', $data);
  }
}
