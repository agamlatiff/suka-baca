<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                TextInput::make('author')
                    ->label('Penulis')
                    ->required(),
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                TextInput::make('rental_fee')
                    ->label('Biaya Sewa (Rp)')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(5000),
                TextInput::make('total_copies')
                    ->label('Total Eksemplar')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('available_copies')
                    ->label('Eksemplar Tersedia')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('times_borrowed')
                    ->label('Total Dipinjam')
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ]);
    }
}
