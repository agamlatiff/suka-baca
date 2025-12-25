<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(100),
                Select::make('icon')
                    ->label('Icon')
                    ->options(self::getIconOptions())
                    ->searchable()
                    ->native(false)
                    ->placeholder('Pilih icon...'),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
            ]);
    }

    private static function getIconOptions(): array
    {
        return [
            'heroicon-o-academic-cap' => '🎓 Academic Cap',
            'heroicon-o-book-open' => '📖 Book Open',
            'heroicon-o-beaker' => '🧪 Beaker (Science)',
            'heroicon-o-calculator' => '🔢 Calculator (Math)',
            'heroicon-o-globe-alt' => '🌍 Globe (Geography)',
            'heroicon-o-heart' => '❤️ Heart (Romance)',
            'heroicon-o-sparkles' => '✨ Sparkles (Fantasy)',
            'heroicon-o-bolt' => '⚡ Bolt (Action)',
            'heroicon-o-face-smile' => '😊 Smile (Comedy)',
            'heroicon-o-puzzle-piece' => '🧩 Puzzle (Mystery)',
            'heroicon-o-users' => '👥 Users (Biography)',
            'heroicon-o-clock' => '⏰ Clock (History)',
            'heroicon-o-computer-desktop' => '💻 Computer (Technology)',
            'heroicon-o-paint-brush' => '🎨 Paint Brush (Art)',
            'heroicon-o-musical-note' => '🎵 Music',
            'heroicon-o-film' => '🎬 Film (Entertainment)',
            'heroicon-o-building-library' => '🏛️ Library (Literature)',
            'heroicon-o-language' => '🗣️ Language',
            'heroicon-o-briefcase' => '💼 Briefcase (Business)',
            'heroicon-o-chart-bar' => '📊 Chart (Economics)',
        ];
    }
}
