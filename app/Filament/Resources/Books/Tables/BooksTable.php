<?php

namespace App\Filament\Resources\Books\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author')
                    ->label('Penulis')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rental_fee')
                    ->label('Biaya Sewa')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('available_copies')
                    ->label('Tersedia')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('total_copies')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('times_borrowed')
                    ->label('Dipinjam')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                EditAction::make()->label('Edit'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Buku')
            ->emptyStateDescription('Klik "Baru" untuk menambahkan buku pertama.');
    }
}
