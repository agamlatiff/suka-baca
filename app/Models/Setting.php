<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'key',
    'value',
    'description',
  ];

  /**
   * Get a setting value by key (cached for 1 hour).
   *
   * @param string $key
   * @param mixed $default
   * @return mixed
   */
  public static function get(string $key, mixed $default = null): mixed
  {
    return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
      $setting = static::where('key', $key)->first();
      return $setting ? $setting->value : $default;
    });
  }

  /**
   * Set a setting value by key and clear cache.
   *
   * @param string $key
   * @param mixed $value
   * @return bool
   */
  public static function set(string $key, mixed $value): bool
  {
    // Clear the cache for this setting
    Cache::forget("setting_{$key}");

    return static::updateOrCreate(
      ['key' => $key],
      ['value' => $value]
    ) ? true : false;
  }

  /**
   * Get the late fee per day setting.
   *
   * @return float
   */
  public static function getLateFeePerDay(): float
  {
    return (float) static::get('late_fee_per_day', 2000);
  }

  /**
   * Get the max borrow days setting.
   *
   * @return int
   */
  public static function getMaxBorrowDays(): int
  {
    return (int) static::get('max_borrow_days', 14);
  }

  /**
   * Get the max books per user setting.
   *
   * @return int
   */
  public static function getMaxBooksPerUser(): int
  {
    return (int) static::get('max_books_per_user', 3);
  }
}
