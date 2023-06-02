<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
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

        Route::get("auth/logout", [AuthController::class, "doLogout"])->name("doLogout");

        Route::controller(TeamController::class)
            ->prefix("team")
            ->group(function () {
                Route::get("/", "showTeam")->name('home');
                Route::post("/search", "search")->name('searchTeam');
            });

        Route::controller(UserController::class)
            ->prefix("user")
            ->group(function () {
                Route::get("/setting", "showSetting")->name('setting');
                Route::get("/logout", "Logout")->name('doLogout');
                Route::post("/deactivate", "deactivate")->name('doDeactivateUser');
                Route::post("/update/profile", "updateData")->name('doUserDataUpdate');
                Route::post("/update/password", "updatePassword")->name('doUserPasswordUpdate');
                Route::post("/update/image", "updateImage")->name('doUserPicturedUpdate');
            });
    });
