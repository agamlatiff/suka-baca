<?php

namespace App\Http\Middleware;

use App\Services\BorrowingValidationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanBorrow
{
  public function __construct(
    protected BorrowingValidationService $validationService
  ) {}

  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next): Response
  {
    $userId = Auth::id();

    if (!$userId) {
      return redirect()->route('login')
        ->with('error', 'Silakan login terlebih dahulu.');
    }

    // Get book_id from request if exists
    $bookId = $request->input('book_id') ?? $request->route('book');

    $validation = $this->validationService->canBorrow($userId, $bookId);

    if (!$validation['valid']) {
      $firstError = $validation['errors'][0] ?? ['message' => 'Anda tidak dapat meminjam saat ini.'];

      if ($request->expectsJson()) {
        return response()->json([
          'success' => false,
          'error' => $firstError['code'] ?? 'VALIDATION_FAILED',
          'message' => $firstError['message'],
          'errors' => $validation['errors'],
        ], 422);
      }

      return back()->with('error', $firstError['message']);
    }

    return $next($request);
  }
}
