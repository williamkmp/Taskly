<?php

namespace App\Http\Controllers;

use App\Logic\FileLogic;
use App\Logic\TeamLogic;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;

class TeamController extends Controller
{
    public function __construct(
        protected TeamLogic $teamLogic,
        protected FileLogic $fileLogic
    ) {
    }

    public function createTeam(Request $request)
    {
        $request->validate([
            "team_name" => "required|min:5|max:30",
            "team_description" => "required|min:5|max:200"
        ]);

        $createdTeam = $this->teamLogic->createTeam(
            Auth::user()->id,
            $request->team_name,
            $request->team_description,
        );

        return redirect()->route("viewTeam", ['team_id' => $createdTeam->id]);
    }

    public function updateData(Request $request)
    {
        $request->validate([
            "team_id" => "required|integer",
            "team_name" => "required|min:5|max:30",
            "team_description" => 'required|min:8|max:200',
        ]);
        $team_id = intval($request->team_id);
        $selectedTeam = Team::find($team_id);

        if($selectedTeam == null){
            return redirect()->back()->withErrors("This team is alredy deleted please contact team owner");
        }

        $selectedTeam->name = $request->team_name;
        $selectedTeam->description = $request->team_description;
        $selectedTeam->save();

        return redirect()->back()->with("notif", ["Success\nEdit succesfully applied!"]);
    }

    public function updateImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => "required|mimes:jpg,jpeg,png|max:10240",
            'team_id' => "required"
        ]);

        $registeredTeam = Team::find(intval($request->team_id));

        if ($validator->fails() || $registeredTeam == null) {
            return response()->json($validator->messages(), HttpResponse::HTTP_BAD_REQUEST);
        }

        $this->fileLogic->storeTeamImage($registeredTeam->id, $request, "image");
        return response()->json(["message" => "success"]);
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
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);

        return view("team")
            ->with("team", $selected_team)
            ->with("owner", $team_owner);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), ["team_name" => "required"]);
        if ($validator->fails()) {
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
