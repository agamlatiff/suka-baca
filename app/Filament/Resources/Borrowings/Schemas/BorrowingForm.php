<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BorrowingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Peminjaman')
                    ->schema([
                        TextInput::make('borrowing_code')
                            ->label('Kode Peminjaman')
                            ->default('BR-' . strtoupper(uniqid()))
                            ->readOnly()
                            ->required(),
                        Select::make('user_id')
                            ->label('Peminjam')
                            ->relationship('user', 'name')
                            ->messages([
                                'required' => 'Wajib dipilih.',
                            ])
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('book_copy_id')
                            ->label('Eksemplar Buku')
                            ->relationship('bookCopy', 'copy_code')
                            ->searchable()
                            ->preload()
                            ->required(),
                        DatePicker::make('borrowed_at')
                            ->label('Tanggal Pinjam')
                            ->default(now())
                            ->required(),
                        DatePicker::make('due_date')
                            ->label('Jatuh Tempo')
                            ->default(now()->addDays(7))
                            ->required(),
                    ])
                    ->columns(2),

                \Filament\Schemas\Components\Section::make('Status & Biaya')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Menunggu Approval',
                                'active' => 'Aktif (Dipinjam)',
                                'returned' => 'Dikembalikan',
                                'overdue' => 'Terlambat',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('pending')
                            ->required(),
                        Toggle::make('is_paid')
                            ->label('Sudah Lunas')
                            ->default(false),
                        TextInput::make('rental_fee')
                            ->label('Biaya Sewa')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(5000),
                        TextInput::make('late_fee')
                            ->label('Denda Keterlambatan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->visible(fn($get) => in_array($get('status'), ['returned', 'overdue'])),
                        TextInput::make('damage_fee')
                            ->label('Denda Kerusakan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->visible(fn($get) => $get('status') === 'returned'),
                        TextInput::make('total_fee')
                            ->label('Total Biaya')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                    ])
                    ->columns(2),

                \Filament\Schemas\Components\Section::make('Pengembalian')
                    ->visible(fn($get) => $get('status') === 'returned')
                    ->schema([
                        DatePicker::make('returned_at')
                            ->label('Tanggal Kembali'),
                        Select::make('return_condition')
                            ->label('Kondisi Pengembalian')
                            ->options([
                                'good' => 'Baik',
                                'damaged' => 'Rusak',
                                'lost' => 'Hilang',
                            ]),
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
