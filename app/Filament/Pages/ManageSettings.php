<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ManageSettings extends Page implements HasForms
{
  use InteractsWithForms;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

  protected static ?string $navigationLabel = 'Pengaturan';

  protected static ?string $title = 'Pengaturan Sistem';

  protected static ?int $navigationSort = 100;

  protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

  protected string $view = 'filament.pages.settings';

  public ?array $librarySettings = [];
  public ?array $borrowingSettings = [];
  public ?array $feeSettings = [];

  public function mount(): void
  {
    $this->librarySettings = [
      'library_name' => Setting::get('library_name', 'Perpustakaan SukaBaca'),
      'library_address' => Setting::get('library_address', ''),
    ];

    $this->borrowingSettings = [
      'max_borrow_days' => Setting::get('max_borrow_days', 7),
      'max_books_per_user' => Setting::get('max_books_per_user', 3),
      'max_extensions' => Setting::get('max_extensions', 2),
      'extension_days' => Setting::get('extension_days', 7),
    ];

    $this->feeSettings = [
      'default_rental_fee' => Setting::get('default_rental_fee', 5000),
      'late_fee_per_day' => Setting::get('late_fee_per_day', 1000),
      'damage_fee_percentage' => Setting::get('damage_fee_percentage', 50),
    ];
  }

  public function saveLibrarySettings(): void
  {
    foreach ($this->librarySettings as $key => $value) {
      Setting::set($key, $value);
    }

    Notification::make()
      ->title('Pengaturan Perpustakaan berhasil disimpan!')
      ->success()
      ->send();
  }

  public function saveBorrowingSettings(): void
  {
    foreach ($this->borrowingSettings as $key => $value) {
      Setting::set($key, $value);
    }

    Notification::make()
      ->title('Pengaturan Peminjaman berhasil disimpan!')
      ->success()
      ->send();
  }

  public function saveFeeSettings(): void
  {
    foreach ($this->feeSettings as $key => $value) {
      Setting::set($key, $value);
    }

    Notification::make()
      ->title('Pengaturan Biaya berhasil disimpan!')
      ->success()
      ->send();
  }
}
