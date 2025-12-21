<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Judul'),
                TextEntry::make('author')
                    ->label('Penulis'),
                TextEntry::make('category.name')
                    ->label('Kategori'),
                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('rental_fee')
                    ->label('Biaya Sewa')
                    ->money('IDR'),
                TextEntry::make('total_copies')
                    ->label('Total Eksemplar')
                    ->numeric(),
                TextEntry::make('available_copies')
                    ->label('Eksemplar Tersedia')
                    ->numeric(),
                TextEntry::make('times_borrowed')
                    ->label('Total Dipinjam')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
                TextEntry::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i'),
            ]);
    }
}
