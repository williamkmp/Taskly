<?php

namespace App\Providers;

use App\Logic\FileLogic;
use App\Logic\UserLogic;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $userLogic = new UserLogic();
        $fileLogic = new FileLogic();
        $this->app->instance(UserLogic::class, $userLogic);
        $this->app->instance(FileLogic::class, $fileLogic);
    }

    public function boot(): void
    {
    }
}
