<?php

namespace App\Filament\Resources\Books\Pages;

use App\Filament\Resources\Books\BookResource;
use App\Models\BookCopy;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function afterCreate(): void
    {
        $book = $this->record;
        $copiesCount = $this->data['copies_count'] ?? 1;

        // Generate copies with auto-generated codes
        for ($i = 1; $i <= $copiesCount; $i++) {
            $copyCode = sprintf('BK%03d-C%02d', $book->id, $i);

            BookCopy::create([
                'book_id' => $book->id,
                'copy_code' => $copyCode,
                'status' => 'available',
            ]);
        }

        // Update book copy counts
        $book->update([
            'total_copies' => $copiesCount,
            'available_copies' => $copiesCount,
        ]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Buku berhasil ditambahkan';
    }
}
