<?php

namespace App\Http\Controllers;

use App\Logic\TeamLogic;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;

class TeamController extends Controller
{
    public function __construct(protected TeamLogic $teamLogic)
    {
    }

    // public function createTeam(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         "team_name" => "required|min:5|max:30",
    //         "team_description" => "required|min:5|max:225"
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->messages(), HttpResponse::HTTP_BAD_REQUEST);
    //     }

    //     $createdTeam = $this->teamLogic->createTeam(
    //         Auth::user()->id,
    //         $request->team_name,
    //         $request->team_description,
    //     );

    //     return response()->json(["redirectUrl" => route("viewTeam", ["team_id" => $createdTeam->id])], HttpResponse::HTTP_OK);
    // }

    public function createTeam(Request $request)
    {
         $request->validate([
            "team_name" => "required|min:5|max:30",
            "team_description" => "required|min:5|max:225"
        ]);

        $createdTeam = $this->teamLogic->createTeam(
            Auth::user()->id,
            $request->team_name,
            $request->team_description,
        );

        return redirect()->route("viewTeam", ['team_id' => $createdTeam->id]);
    }


    public function showTeams()
    {

        $user = User::find(Auth::user()->id);
        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"]);
        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"]);

        return view("teams")
            ->with("teams", $teams)
            ->with("invites", $invites);
    }

    public function showTeam($team_id)
    {
        $team_id = intval($team_id);
        $selected_team = Team::find($team_id);
        return view("team")
            ->with("team", $selected_team);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), ["team_name" => "required"]);
        if($validator->fails()){
            return redirect()->route("home");
        }

        // @php-ignore
        $request->session()->flash("__old_team_name", $request->team_name);
        $user = User::find(Auth::user()->id);
        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"], $request->team_name);
        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"], $request->team_name);

        return view("teams")
            ->with("teams", $teams)
            ->with("invites", $invites);
    }
}
