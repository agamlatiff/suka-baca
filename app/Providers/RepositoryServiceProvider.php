<?php

namespace App\Providers;

use App\Contracts\Repositories\BookRepositoryInterface;
use App\Contracts\Repositories\BorrowingRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\PaymentRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Repositories\Eloquent\BookRepository;
use App\Repositories\Eloquent\BorrowingRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
  /**
   * All repository bindings.
   *
   * @var array<class-string, class-string>
   */
  public array $bindings = [
    BookRepositoryInterface::class => BookRepository::class,
    CategoryRepositoryInterface::class => CategoryRepository::class,
    BorrowingRepositoryInterface::class => BorrowingRepository::class,
    UserRepositoryInterface::class => UserRepository::class,
    PaymentRepositoryInterface::class => PaymentRepository::class,
  ];

  /**
   * Register services.
   */
  public function register(): void
  {
    foreach ($this->bindings as $interface => $implementation) {
      $this->app->bind($interface, $implementation);
    }
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
