<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
  protected static ?int $sort = 1;

  protected function getStats(): array
  {
    $totalBooks = Book::count();
    $totalCopies = BookCopy::count();
    $availableCopies = BookCopy::where('status', 'available')->count();
    $borrowedCopies = BookCopy::where('status', 'borrowed')->count();
    $totalUsers = User::where('role', 'user')->count();
    $activeBorrowings = Borrowing::whereNull('returned_at')->count();
    $unpaidCount = Borrowing::where('is_paid', false)->count();
    $overdueCount = Borrowing::whereNull('returned_at')
      ->where('due_date', '<', now())
      ->count();

    return [
      Stat::make('Total Buku', $totalBooks)
        ->description('Judul buku')
        ->icon('heroicon-o-book-open')
        ->color('primary'),

      Stat::make('Total Eksemplar', $totalCopies)
        ->description("Tersedia: {$availableCopies}")
        ->icon('heroicon-o-rectangle-stack')
        ->color('info'),

      Stat::make('Sedang Dipinjam', $borrowedCopies)
        ->description("Dari {$totalCopies} eksemplar")
        ->icon('heroicon-o-arrow-right-circle')
        ->color('warning'),

      Stat::make('Peminjaman Aktif', $activeBorrowings)
        ->description($overdueCount > 0 ? "{$overdueCount} terlambat" : 'Semua tepat waktu')
        ->icon('heroicon-o-clipboard-document-list')
        ->color($overdueCount > 0 ? 'danger' : 'success'),

      Stat::make('Total Anggota', $totalUsers)
        ->description('User terdaftar')
        ->icon('heroicon-o-users')
        ->color('gray'),

      Stat::make('Belum Dibayar', $unpaidCount)
        ->description('Transaksi pending')
        ->icon('heroicon-o-banknotes')
        ->color($unpaidCount > 0 ? 'danger' : 'success'),
    ];
  }
}
