<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentBorrowingsTable extends TableWidget
{
  protected static ?int $sort = 2;

  protected int|string|array $columnSpan = 'full';

  protected static ?string $heading = 'Peminjaman Terbaru';

  public function table(Table $table): Table
  {
    return $table
      ->query(
        Borrowing::query()
          ->with(['user', 'book', 'bookCopy'])
          ->orderBy('created_at', 'desc')
          ->limit(10)
      )
      ->columns([
        TextColumn::make('code')
          ->label('Kode')
          ->searchable(),
        TextColumn::make('user.name')
          ->label('Peminjam')
          ->searchable(),
        TextColumn::make('book.title')
          ->label('Buku')
          ->limit(25),
        TextColumn::make('borrowed_at')
          ->label('Tanggal Pinjam')
          ->date('d M Y')
          ->sortable(),
        TextColumn::make('due_date')
          ->label('Jatuh Tempo')
          ->date('d M Y')
          ->color(
            fn(Borrowing $record): string =>
            $record->returned_at === null && $record->due_date < now() ? 'danger' : 'gray'
          ),
        TextColumn::make('status')
          ->label('Status')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'active' => 'info',
            'returned' => 'success',
            'overdue' => 'danger',
            default => 'gray',
          })
          ->formatStateUsing(fn(string $state): string => match ($state) {
            'active' => 'Aktif',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat',
            default => $state,
          }),
        IconColumn::make('is_paid')
          ->label('Lunas')
          ->boolean()
          ->trueIcon('heroicon-o-check-circle')
          ->falseIcon('heroicon-o-x-circle')
          ->trueColor('success')
          ->falseColor('danger'),
      ])
      ->paginated(false);
  }
}
