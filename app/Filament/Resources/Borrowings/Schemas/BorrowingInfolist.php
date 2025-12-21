<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BorrowingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('borrowing_code'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('bookCopy.id')
                    ->label('Book copy'),
                TextEntry::make('borrowed_at')
                    ->date(),
                TextEntry::make('due_date')
                    ->date(),
                TextEntry::make('returned_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('rental_fee')
                    ->numeric(),
                TextEntry::make('late_fee')
                    ->numeric(),
                TextEntry::make('total_fee')
                    ->numeric(),
                IconEntry::make('is_paid')
                    ->boolean(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('days_late')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
