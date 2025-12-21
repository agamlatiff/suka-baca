<?php

namespace App\Filament\Resources\Borrowings\Tables;

use App\Models\Borrowing;
use App\Models\Setting;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BorrowingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Peminjaman')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('book.title')
                    ->label('Buku')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('bookCopy.copy_code')
                    ->label('Kode Eksemplar')
                    ->searchable(),
                TextColumn::make('borrowed_at')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(
                        fn(Borrowing $record): string =>
                        $record->returned_at === null && $record->due_date < now() ? 'danger' : 'gray'
                    ),
                TextColumn::make('returned_at')
                    ->label('Dikembalikan')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('total_fee')
                    ->label('Total Biaya')
                    ->money('IDR')
                    ->sortable(),
                IconColumn::make('is_paid')
                    ->label('Lunas')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'info',
                        'returned' => 'success',
                        'overdue' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'returned' => 'Dikembalikan',
                        'overdue' => 'Terlambat',
                        default => $state,
                    }),
            ])
            ->defaultSort('borrowed_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'returned' => 'Dikembalikan',
                        'overdue' => 'Terlambat',
                    ]),
                TernaryFilter::make('is_paid')
                    ->label('Status Pembayaran')
                    ->trueLabel('Lunas')
                    ->falseLabel('Belum Lunas')
                    ->placeholder('Semua'),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                Action::make('return')
                    ->label('Kembalikan')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pengembalian')
                    ->modalDescription(
                        fn(Borrowing $record) =>
                        "Anda akan mengembalikan buku \"{$record->book->title}\"."
                    )
                    ->visible(fn(Borrowing $record) => $record->returned_at === null)
                    ->action(function (Borrowing $record) {
                        $now = Carbon::now();
                        $dueDate = Carbon::parse($record->due_date);

                        // Calculate late fee
                        $lateFee = 0;
                        $daysLate = 0;
                        if ($now->gt($dueDate)) {
                            $daysLate = $now->diffInDays($dueDate);
                            $lateFeePerDay = Setting::get('late_fee_per_day', 1000);
                            $lateFee = $daysLate * $lateFeePerDay;
                        }

                        // Update borrowing
                        $record->update([
                            'returned_at' => $now,
                            'late_fee' => $lateFee,
                            'total_fee' => $record->rental_fee + $lateFee,
                            'days_late' => $daysLate,
                        ]);

                        // Update copy status
                        $record->bookCopy->update(['status' => 'available']);

                        // Update book counts
                        $record->book->increment('available_copies');
                        $record->book->increment('times_borrowed');

                        Notification::make()
                            ->title('Buku berhasil dikembalikan')
                            ->body($lateFee > 0 ? "Denda keterlambatan: Rp " . number_format($lateFee, 0, ',', '.') : null)
                            ->success()
                            ->send();
                    }),
                Action::make('markPaid')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembayaran')
                    ->modalDescription(
                        fn(Borrowing $record) =>
                        "Total biaya: Rp " . number_format($record->total_fee, 0, ',', '.')
                    )
                    ->visible(fn(Borrowing $record) => !$record->is_paid)
                    ->action(function (Borrowing $record) {
                        $record->update(['is_paid' => true]);

                        Notification::make()
                            ->title('Pembayaran berhasil dicatat')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Peminjaman')
            ->emptyStateDescription('Daftar peminjaman akan muncul di sini.');
    }
}
