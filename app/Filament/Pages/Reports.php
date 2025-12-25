<?php

namespace App\Filament\Pages;

use App\Models\Borrowing;
use App\Services\BookService;
use App\Services\BorrowingService;
use App\Services\DashboardService;
use App\Services\PaymentService;
use App\Services\UserService;
use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Reports extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Laporan & Statistik';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.reports';

    public ?string $activeTab = 'popular-books';

    public ?string $periodFilter = 'month';

    public ?string $startDate = null;

    public ?string $endDate = null;

    protected BookService $bookService;
    protected UserService $userService;
    protected PaymentService $paymentService;
    protected BorrowingService $borrowingService;
    protected DashboardService $dashboardService;

    public function boot(
        BookService $bookService,
        UserService $userService,
        PaymentService $paymentService,
        BorrowingService $borrowingService,
        DashboardService $dashboardService
    ): void {
        $this->bookService = $bookService;
        $this->userService = $userService;
        $this->paymentService = $paymentService;
        $this->borrowingService = $borrowingService;
        $this->dashboardService = $dashboardService;
    }

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function getPopularBooks(): array
    {
        return $this->bookService->getPopularBooks(10)
            ->map(fn($book) => [
                'title' => $book->title,
                'author' => $book->author,
                'count' => $book->times_borrowed,
            ])
            ->toArray();
    }

    public function getActiveBorrowers(): array
    {
        return $this->userService->getTopBorrowers(10, $this->startDate, $this->endDate)
            ->map(fn($user) => [
                'name' => $user->name,
                'email' => $user->email,
                'count' => $user->borrowings_count,
            ])
            ->toArray();
    }

    public function getRevenueData(): array
    {
        return $this->dashboardService->getMonthlyRevenue(12);
    }

    public function getRevenueStats(): array
    {
        return $this->paymentService->getRevenueStats();
    }

    public function getBorrowingStats(): array
    {
        return $this->borrowingService->getBorrowingStats($this->startDate, $this->endDate);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Borrowing::query()
                    ->with(['user', 'book', 'bookCopy'])
                    ->whereBetween('borrowed_at', [$this->startDate, $this->endDate])
                    ->latest('borrowed_at')
            )
            ->columns([
                TextColumn::make('borrowing_code')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable(),
                TextColumn::make('book.title')
                    ->label('Buku')
                    ->limit(25),
                TextColumn::make('borrowed_at')
                    ->label('Tgl Pinjam')
                    ->date('d M Y'),
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y'),
                TextColumn::make('returned_at')
                    ->label('Dikembalikan')
                    ->date('d M Y')
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'info',
                        'returned' => 'success',
                        'overdue' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('total_fee')
                    ->label('Total')
                    ->money('IDR'),
            ])
            ->defaultSort('borrowed_at', 'desc');
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function updatePeriod(): void
    {
        // Triggers reactivity
    }

    public function exportBorrowings()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\BorrowingsExport($this->startDate, $this->endDate),
            'laporan-peminjaman-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
