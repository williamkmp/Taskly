<?php

namespace App\Providers;

use App\Logic\BoardLogic;
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
        $boardLogic = new BoardLogic();

        $this->app->instance(UserLogic::class, $userLogic);
        $this->app->instance(FileLogic::class, $fileLogic);
        $this->app->instance(TeamLogic::class, $teamLogic);
        $this->app->instance(BoardLogic::class, $boardLogic);
    }

    public function boot(): void
    {
    }
}
