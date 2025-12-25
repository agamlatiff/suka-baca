<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Payment;
use Filament\Forms\Components\Textarea;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_code')
                    ->label('Kode Pembayaran')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('borrowing.borrowing_code')
                    ->label('Kode Pinjam')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'rental' => 'Sewa',
                        'extension' => 'Perpanjangan',
                        'late_fee' => 'Denda',
                        'damage_fee' => 'Ganti Rugi',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'rental' => 'primary',
                        'extension' => 'info',
                        'late_fee' => 'danger',
                        'damage_fee' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->numeric()
                    ->prefix('Rp ')
                    ->sortable(),
                TextColumn::make('method')
                    ->label('Metode')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'transfer' => 'success',
                        'cash' => 'warning',
                        default => 'gray',
                    }),
                ImageColumn::make('proof_image')
                    ->label('Bukti')
                    ->simpleLightbox(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'verified' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        'pending' => 'Menunggu',
                        default => $state,
                    }),
                TextColumn::make('verifier.name')
                    ->label('Diverifikasi Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe Pembayaran')
                    ->options([
                        'rental' => 'Sewa',
                        'extension' => 'Perpanjangan',
                        'late_fee' => 'Denda',
                        'damage_fee' => 'Ganti Rugi',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Payment $record) => $record->status === 'pending')
                    ->action(fn(Payment $record) => $record->verify(auth()->id())),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Payment $record) => $record->status === 'pending')
                    ->form([
                        Textarea::make('rejection_notes')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(fn(Payment $record, array $data) => $record->reject(auth()->id(), $data['rejection_notes'])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
