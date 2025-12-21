<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Pengaturan')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'late_fee_per_day' => 'Denda Per Hari',
                        'max_borrow_days' => 'Maks Hari Pinjam',
                        'max_books_per_user' => 'Maks Buku Per User',
                        'default_rental_fee' => 'Biaya Sewa Default',
                        'library_name' => 'Nama Perpustakaan',
                        'library_address' => 'Alamat Perpustakaan',
                        default => $state,
                    }),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label('Edit'),
            ])
            ->emptyStateHeading('Tidak Ada Pengaturan')
            ->emptyStateDescription('Jalankan php artisan db:seed untuk membuat pengaturan default.');
    }
}
