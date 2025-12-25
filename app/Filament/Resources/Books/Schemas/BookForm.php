<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Buku')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Cover Buku')
                            ->image()
                            ->imageEditor()
                            ->directory('books')
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('slug', Str::slug($state))
                            ),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('author')
                            ->label('Penulis')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('publisher')
                            ->label('Penerbit')
                            ->maxLength(255),
                        TextInput::make('year')
                            ->label('Tahun Terbit')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y')),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('isbn')
                            ->label('ISBN')
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Sinopsis/Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Harga & Stok')
                    ->schema([
                        TextInput::make('rental_fee')
                            ->label('Biaya Sewa (7 hari)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(5000),
                        TextInput::make('book_price')
                            ->label('Harga Buku (untuk denda)')
                            ->numeric()
                            ->prefix('Rp')
                            ->helperText('Digunakan untuk perhitungan denda rusak/hilang'),
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
