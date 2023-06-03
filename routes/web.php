<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return redirect()->route("login");
});

Route::middleware("guest")->get("auth/login", [AuthController::class, "showLogin"])->name("login");
Route::middleware("guest")->post("auth/login", [AuthController::class, "doLogin"])->name("doLogin");
Route::middleware("guest")->get("auth/register", [AuthController::class, "showRegister"])->name("register");
Route::middleware("guest")->post("auth/register", [AuthController::class, "doRegister"])->name("doRegister");


Route::middleware(["auth", "auth.session"])->get("team", [TeamController::class, "showTeams"])->name("home");
Route::middleware(["auth", "auth.session"])->post("team", [TeamController::class, "createTeam"])->name("doCreateTeam");
Route::middleware(["auth", "auth.session"])->post("team/search", [TeamController::class, "search"])->name("searchTeam");
Route::middleware(["auth", "auth.session"])->get("team/view/{team_id}", [TeamController::class, "showTeam"])->name("viewTeam");
Route::middleware(["auth", "auth.session"])->post("team/update/profile", [TeamController::class, "updateData"])->name("doTeamDataUpdate");
Route::middleware(["auth", "auth.session"])->post("team/update/picture", [TeamController::class, "updateImage"])->name("doChangeTeamImage");

Route::middleware(["auth", "auth.session"])->get("user/setting", [UserController::class, "showSetting"])->name("setting");
Route::middleware(["auth", "auth.session"])->get("user/logout", [UserController::class, "logout"])->name("doLogout");
Route::middleware(["auth", "auth.session"])->post("user/deactivate", [UserController::class, "deactivate"])->name("doDeactivateUser");
Route::middleware(["auth", "auth.session"])->post("user/update/profile", [UserController::class, "updateData"])->name("doUserDataUpdate");
Route::middleware(["auth", "auth.session"])->post("user/update/password", [UserController::class, "updatePassword"])->name("doUserPasswordUpdate");
Route::middleware(["auth", "auth.session"])->post("user/update/image", [UserController::class, "updateImage"])->name("doUserPicturedUpdate");
