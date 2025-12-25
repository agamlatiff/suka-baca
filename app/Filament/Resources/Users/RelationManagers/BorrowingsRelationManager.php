<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Borrowings\BorrowingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class BorrowingsRelationManager extends RelationManager
{
    protected static string $relationship = 'borrowings';

    protected static ?string $relatedResource = BorrowingResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
