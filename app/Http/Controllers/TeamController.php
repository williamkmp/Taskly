<?php

namespace App\Http\Controllers;

use App\Logic\TeamLogic;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), ["team_name" => "required"]);
        if($validator->fails()){
            return redirect()->route("home");
        }

        $request->session()->flash("__old_team_name", $request->team_name);
        $user = User::find(Auth::user()->id);
        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"], $request->team_name);
        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"], $request->team_name);

        return view("teams")
            ->with("teams", $teams)
            ->with("invites", $invites);
    }
}
