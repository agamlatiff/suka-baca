<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Kunci Pengaturan')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('value')
                    ->label('Nilai')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->disabled()
                    ->rows(2),
            ]);
    }
}
