<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Buku')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('author')
                            ->label('Penulis')
                            ->required()
                            ->maxLength(255),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Harga & Stok')
                    ->schema([
                        TextInput::make('rental_fee')
                            ->label('Biaya Sewa (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(5000),
                        TextInput::make('copies_count')
                            ->label('Jumlah Eksemplar')
                            ->helperText('Akan dibuat otomatis saat buku disimpan')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(100)
                            ->visibleOn('create'),
                        TextInput::make('total_copies')
                            ->label('Total Eksemplar')
                            ->numeric()
                            ->disabled()
                            ->hiddenOn('create'),
                        TextInput::make('available_copies')
                            ->label('Tersedia')
                            ->numeric()
                            ->disabled()
                            ->hiddenOn('create'),
                    ])
                    ->columns(2),
            ]);
    }
}
