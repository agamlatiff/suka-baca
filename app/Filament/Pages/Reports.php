<?php

namespace App\Filament\Pages;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Payment;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

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

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function getPopularBooks(): array
    {
        return Book::select('books.*')
            ->withCount(['borrowings' => function ($query) {
                $query->whereBetween('borrowed_at', [$this->startDate, $this->endDate]);
            }])
            ->orderByDesc('borrowings_count')
            ->limit(10)
            ->get()
            ->map(fn($book) => [
                'title' => $book->title,
                'author' => $book->author,
                'count' => $book->borrowings_count,
            ])
            ->toArray();
    }

    public function getActiveBorrowers(): array
    {
        return User::select('users.*')
            ->withCount(['borrowings' => function ($query) {
                $query->whereBetween('borrowed_at', [$this->startDate, $this->endDate]);
            }])
            ->where('role', 'user')
            ->orderByDesc('borrowings_count')
            ->limit(10)
            ->get()
            ->map(fn($user) => [
                'name' => $user->name,
                'email' => $user->email,
                'count' => $user->borrowings_count,
            ])
            ->toArray();
    }

    public function getRevenueData(): array
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('Y-m');
            $monthLabel = $date->translatedFormat('M Y');

            $revenue = Payment::where('status', 'verified')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $months[] = [
                'month' => $monthLabel,
                'revenue' => (int) $revenue,
            ];
        }

        return $months;
    }

    public function getRevenueStats(): array
    {
        $today = Payment::where('status', 'verified')
            ->whereDate('created_at', today())
            ->sum('amount');

        $thisWeek = Payment::where('status', 'verified')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');

        $thisMonth = Payment::where('status', 'verified')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return [
            'today' => $today,
            'week' => $thisWeek,
            'month' => $thisMonth,
        ];
    }

    public function getBorrowingStats(): array
    {
        $query = Borrowing::query()
            ->whereBetween('borrowed_at', [$this->startDate, $this->endDate]);

        return [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('status', 'active')->count(),
            'returned' => (clone $query)->where('status', 'returned')->count(),
            'overdue' => (clone $query)->where('status', 'overdue')->count(),
            'total_revenue' => (clone $query)->sum('total_fee'),
            'total_late_fee' => (clone $query)->sum('late_fee'),
        ];
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
