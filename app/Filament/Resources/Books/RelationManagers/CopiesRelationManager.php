<?php

namespace App\Filament\Resources\Books\RelationManagers;

use App\Models\BookCopy;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CopiesRelationManager extends RelationManager
{
  protected static string $relationship = 'copies';

  protected static ?string $title = 'Eksemplar Buku';

  protected static ?string $modelLabel = 'Eksemplar';

  protected static ?string $pluralModelLabel = 'Eksemplar';

  public function form(Schema $schema): Schema
  {
    return $schema
      ->components([
        TextInput::make('copy_code')
          ->label('Kode Eksemplar')
          ->disabled()
          ->dehydrated(false),
        Select::make('status')
          ->label('Status')
          ->options([
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'maintenance' => 'Perawatan',
            'lost' => 'Hilang',
          ])
          ->required()
          ->default('available'),
        Textarea::make('notes')
          ->label('Catatan')
          ->rows(2),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('copy_code')
          ->label('Kode Eksemplar')
          ->sortable()
          ->searchable(),
        TextColumn::make('status')
          ->label('Status')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'available' => 'success',
            'borrowed' => 'warning',
            'maintenance' => 'gray',
            'lost' => 'danger',
            default => 'gray',
          })
          ->formatStateUsing(fn(string $state): string => match ($state) {
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'maintenance' => 'Perawatan',
            'lost' => 'Hilang',
            default => $state,
          }),
        TextColumn::make('notes')
          ->label('Catatan')
          ->limit(30)
          ->toggleable(isToggledHiddenByDefault: true),
        TextColumn::make('updated_at')
          ->label('Diperbarui')
          ->dateTime('d M Y')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        SelectFilter::make('status')
          ->label('Status')
          ->options([
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'maintenance' => 'Perawatan',
            'lost' => 'Hilang',
          ]),
      ])
      ->headerActions([
        CreateAction::make()
          ->label('Tambah Eksemplar')
          ->mutateDataUsing(function (array $data): array {
            $book = $this->getOwnerRecord();
            $nextNumber = $book->copies()->count() + 1;
            $data['copy_code'] = sprintf('BK%03d-C%02d', $book->id, $nextNumber);
            $data['status'] = $data['status'] ?? 'available';
            return $data;
          })
          ->after(function () {
            $this->syncBookCopyCounts();
          }),
      ])
      ->rowActions([
        EditAction::make()
          ->label('Edit')
          ->after(function () {
            $this->syncBookCopyCounts();
          }),
        DeleteAction::make()
          ->label('Hapus')
          ->after(function () {
            $this->syncBookCopyCounts();
          }),
      ])
      ->bulkActions([
        BulkActionGroup::make([
          DeleteBulkAction::make()
            ->label('Hapus')
            ->after(function () {
              $this->syncBookCopyCounts();
            }),
        ]),
      ])
      ->emptyStateHeading('Belum Ada Eksemplar')
      ->emptyStateDescription('Klik "Tambah Eksemplar" untuk menambahkan eksemplar buku.');
  }

  /**
   * Sync the book's total_copies and available_copies counts.
   */
  protected function syncBookCopyCounts(): void
  {
    $book = $this->getOwnerRecord();
    $book->update([
      'total_copies' => $book->copies()->count(),
      'available_copies' => $book->copies()->where('status', 'available')->count(),
    ]);
  }
}
