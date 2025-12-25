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
                ViewAction::make(),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Borrowing $record) => $record->status === 'pending')
                    ->action(function (Borrowing $record) {
                        // Assign available copy if not assigned
                        if (!$record->book_copy_id) {
                            $copy = $record->book->copies()->where('status', 'available')->first();
                            if (!$copy) {
                                Notification::make()
                                    ->title('Gagal')
                                    ->body('Tidak ada eksemplar tersedia.')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            $record->update(['book_copy_id' => $copy->id]);
                            $copy->update(['status' => 'borrowed']);
                            // Sync book counts
                            $record->book->update([
                                'available_copies' => $record->book->copies()->where('status', 'available')->count()
                            ]);
                        } else {
                            $record->bookCopy->update(['status' => 'borrowed']);
                        }

                        $record->update(['status' => 'active']);

                        Notification::make()
                            ->title('Peminjaman disetujui')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Borrowing $record) => $record->status === 'pending')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (Borrowing $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'notes' => $data['notes']
                        ]);

                        Notification::make()
                            ->title('Permintaan ditolak')
                            ->success()
                            ->send();
                    }),
                Action::make('return')
                    ->label('Kembalikan')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pengembalian')
                    ->form([
                        \Filament\Forms\Components\Select::make('return_condition')
                            ->label('Kondisi Buku')
                            ->options([
                                'good' => 'Baik',
                                'damaged' => 'Rusak',
                                'lost' => 'Hilang',
                            ])
                            ->default('good')
                            ->required()
                            ->reactive(),
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->placeholder('Keterangan kerusakan/kehilangan...'),
                    ])
                    ->visible(fn(Borrowing $record) => in_array($record->status, ['active', 'overdue']))
                    ->action(function (Borrowing $record, array $data) {
                        $now = Carbon::now();
                        $dueDate = Carbon::parse($record->due_date);

                        // Calculate late fee
                        $lateFee = 0;
                        $daysLate = 0;
                        if ($now->gt($dueDate)) {
                            $daysLate = $now->diffInDays($dueDate);
                            $lateFeePerDay = Setting::get('late_fee_per_day', 1000); // 1000 per hari default
                            $lateFee = $daysLate * $lateFeePerDay;
                        }

                        // Calculate damage/lost fee
                        $damageFee = 0;
                        if ($data['return_condition'] === 'damaged') {
                            $damageFee = 50000; // Flat fee or %? Using flat for now
                        } elseif ($data['return_condition'] === 'lost') {
                            $damageFee = $record->book->book_price ?? 100000; // Book price or default
                        }

                        // Update borrowing
                        $record->update([
                            'returned_at' => $now,
                            'status' => 'returned',
                            'return_condition' => $data['return_condition'],
                            'late_fee' => $lateFee,
                            'damage_fee' => $damageFee,
                            'total_fee' => $record->rental_fee + $lateFee + $damageFee,
                            'days_late' => $daysLate,
                            'notes' => $data['notes'],
                        ]);

                        // Update copy status
                        if ($record->bookCopy) {
                            $newStatus = match ($data['return_condition']) {
                                'good' => 'available',
                                'damaged' => 'damaged', // But still returned to library?
                                'lost' => 'lost',
                                default => 'available',
                            };
                            $record->bookCopy->update(['status' => $newStatus]);
                        }

                        // Update book counts
                        $record->book->update([
                            'total_copies' => $record->book->copies()->count(),
                            'available_copies' => $record->book->copies()->where('status', 'available')->count(),
                            'times_borrowed' => $record->book->times_borrowed + 1
                        ]);

                        Notification::make()
                            ->title('Buku berhasil dikembalikan')
                            ->body("Denda: Rp " . number_format($lateFee + $damageFee, 0, ',', '.'))
                            ->success()
                            ->send();
                    }),
                Action::make('markPaid')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn(Borrowing $record) => !$record->is_paid && $record->status !== 'rejected')
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
