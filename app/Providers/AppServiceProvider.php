<?php

namespace App\Providers;

use App\Logic\FileLogic;
use App\Logic\TeamLogic;
use App\Logic\UserLogic;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $userLogic = new UserLogic();
        $fileLogic = new FileLogic();
        $teamLogic = new TeamLogic();

        $this->app->instance(UserLogic::class, $userLogic);
        $this->app->instance(FileLogic::class, $fileLogic);
        $this->app->instance(TeamLogic::class, $teamLogic);
    }

    public function boot(): void
    {
    }
}
