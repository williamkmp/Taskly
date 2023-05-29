<?php

namespace App\Providers;

use App\Logic\UserLogic;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $userLogic = new UserLogic();
        $this->app->instance(UserLogic::class, $userLogic);
    }

    public function boot(): void
    {
    }
}
