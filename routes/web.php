<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return redirect()->route("login");
});

Route::middleware("guest")
    ->controller(AuthController::class)
    ->prefix("auth")
    ->group(function () {

        Route::get("login", "showLogin")->name("login");
        Route::get("register", "showRegister")->name("register");
        Route::post("register", "doRegister")->name("doRegister");
        Route::post("login", "doLogin")->name("doLogin");
    });

Route::middleware(["auth", "auth.session"])
    ->group(function () {

        Route::controller(TeamController::class)
            ->prefix("team")
            ->group(function () {
                Route::get("/", "showTeam")->name('home');
            });

        Route::controller(SettingController::class)
            ->prefix("setting")
            ->group(function () {
                Route::get("/", "showSetting")->name('setting');
            });
    });
