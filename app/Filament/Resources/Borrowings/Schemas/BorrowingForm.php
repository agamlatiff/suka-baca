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
                TextInput::make('borrowing_code')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('book_copy_id')
                    ->relationship('bookCopy', 'id')
                    ->required(),
                DatePicker::make('borrowed_at')
                    ->required(),
                DatePicker::make('due_date')
                    ->required(),
                DatePicker::make('returned_at'),
                TextInput::make('rental_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('late_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('is_paid')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'returned' => 'Returned', 'overdue' => 'Overdue'])
                    ->default('active')
                    ->required(),
                TextInput::make('days_late')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
