<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
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
Route::middleware(["auth", "auth.session"])->get("team/search", [TeamController::class, "search"])->name("searchTeam");
Route::middleware(["auth", "auth.session"])->get("team/invite/accept/{user_id}/{team_id}", [TeamController::class, "acceptInvite"])->name("acceptTeamInvite");
Route::middleware(["auth", "auth.session"])->get("team/invite/reject/{user_id}/{team_id}", [TeamController::class, "rejectInvite"])->name("rejectTeamInvite");
Route::middleware(["auth", "auth.session"])->get("team/invite/{user_id}/{team_id}", [TeamController::class, "getInvite"])->name("getInvite");
Route::middleware(["auth", "auth.session"])->post("team/invite", [TeamController::class, "inviteMembers"])->name("doInviteMembers");
Route::middleware(["auth", "auth.session"])->get("team/board/search", [TeamController::class, "searchBoard"])->name("searchBoard");
Route::middleware(["auth", "auth.session"])->post("team/board/delete", [TeamController::class, "deleteMembers"])->name("deleteTeamMember");
Route::middleware(["auth", "auth.session"])->get("team/view/{team_id}", [TeamController::class, "showTeam"])->name("viewTeam");
Route::middleware(["auth", "auth.session"])->post("team/update/profile", [TeamController::class, "updateData"])->name("doTeamDataUpdate");
Route::middleware(["auth", "auth.session"])->post("team/update/picture", [TeamController::class, "updateImage"])->name("doChangeTeamImage");

Route::middleware(["auth", "auth.session"])->post("board", [BoardController::class, "createBoard"])->name("createBoard");
Route::middleware(["auth", "auth.session"])->get("board/{board_id}", [BoardController::class, "showBoard"])->name("board");

Route::middleware(["auth", "auth.session"])->get("user/setting", [UserController::class, "showSetting"])->name("setting");
Route::middleware(["auth", "auth.session"])->get("user/logout", [UserController::class, "logout"])->name("doLogout");
Route::middleware(["auth", "auth.session"])->post("user/deactivate", [UserController::class, "deactivate"])->name("doDeactivateUser");
Route::middleware(["auth", "auth.session"])->post("user/update/profile", [UserController::class, "updateData"])->name("doUserDataUpdate");
Route::middleware(["auth", "auth.session"])->post("user/update/password", [UserController::class, "updatePassword"])->name("doUserPasswordUpdate");
Route::middleware(["auth", "auth.session"])->post("user/update/image", [UserController::class, "updateImage"])->name("doUserPicturedUpdate");
