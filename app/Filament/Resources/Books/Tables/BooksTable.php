<?php

namespace App\Filament\Resources\Books\Tables;

use App\Exports\BooksExport;
use App\Imports\BooksImport;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author')
                    ->label('Penulis')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rental_fee')
                    ->label('Biaya Sewa')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('available_copies')
                    ->label('Tersedia')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('total_copies')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('times_borrowed')
                    ->label('Dipinjam')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(new BooksExport, 'books-' . now()->format('Y-m-d') . '.xlsx');
                    }),
                Action::make('import')
                    ->label('Import Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('warning')
                    ->form([
                        FileUpload::make('file')
                            ->label('File Excel')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                            ->required()
                            ->directory('imports'),
                    ])
                    ->action(function (array $data) {
                        try {
                            Excel::import(new BooksImport, storage_path('app/public/' . $data['file']));
                            Notification::make()
                                ->title('Import berhasil!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Import gagal!')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                EditAction::make()->label('Edit'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Buku')
            ->emptyStateDescription('Klik "Baru" untuk menambahkan buku pertama.');
    }
}
