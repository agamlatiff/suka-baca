<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        TextInput::make('payment_code')
                            ->label('Kode Pembayaran')
                            ->required()
                            ->readOnly(),
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable(),
                        Select::make('borrowing_id')
                            ->label('Peminjaman')
                            ->relationship('borrowing', 'borrowing_code')
                            ->required()
                            ->searchable(),
                        Select::make('type')
                            ->label('Tipe Pembayaran')
                            ->options([
                                'rental' => 'Sewa',
                                'extension' => 'Perpanjangan',
                                'late_fee' => 'Denda',
                                'damage_fee' => 'Ganti Rugi',
                            ])
                            ->required(),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Select::make('method')
                            ->label('Metode')
                            ->options(['cash' => 'Tunai', 'transfer' => 'Transfer'])
                            ->required(),
                    ])
                    ->columns(2),

                \Filament\Schemas\Components\Section::make('Status & Verifikasi')
                    ->schema([
                        FileUpload::make('proof_image')
                            ->label('Bukti Transfer')
                            ->image()
                            ->directory('payments')
                            ->openable()
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Menunggu Verifikasi',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('pending')
                            ->required(),
                        Textarea::make('rejection_notes')
                            ->label('Alasan Penolakan')
                            ->visible(fn($get) => $get('status') === 'rejected')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
