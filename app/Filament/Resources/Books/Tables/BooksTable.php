<?php

namespace App\Filament\Resources\Books\Tables;

use App\Exports\BooksExport;
use App\Imports\BooksImport;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
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
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                EditAction::make()->label('Edit'),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()->label('Hapus'),
                BulkAction::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Collection $records) {
                        return Excel::download(new BooksExport, 'books-' . now()->format('Y-m-d') . '.xlsx');
                    }),
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
            ->emptyStateHeading('Belum Ada Buku')
            ->emptyStateDescription('Klik "Baru" untuk menambahkan buku pertama.');
    }
}
