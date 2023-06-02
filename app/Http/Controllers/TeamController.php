<?php

namespace App\Http\Controllers;

use App\Logic\TeamLogic;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function __construct(protected TeamLogic $teamLogic)
    {
    }

    public function showTeam()
    {
        $user = User::find(Auth::user()->id);
        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"]);
        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"]);

        return view("teams")
            ->with("teams", $teams)
            ->with("invites", $invites);
    }
}
