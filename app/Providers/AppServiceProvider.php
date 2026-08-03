<?php

namespace App\Providers;

use App\Domain\Repostories\Classes\UserRepository;
use App\Domain\Repostories\Interfaces\IUserRepository;
use App\Domain\Responder\Classes\ApiHttpResponder;
use App\Domain\Responder\Interfaces\IApiHttpResponder;
use App\Domain\Services\Classes\AuthService;
use App\Domain\Services\Interfaces\IAuthService;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->scoped(IApiHttpResponder::class, ApiHttpResponder::class);
        $this->app->scoped(IAuthService::class, AuthService::class);
        $this->app->scoped(IUserRepository::class, function () {
            return new UserRepository(new User);
        });

    }
}
