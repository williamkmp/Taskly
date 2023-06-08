<?php

namespace App\Http\Controllers;

use App\Logic\BoardLogic;
use App\Logic\FileLogic;
use App\Logic\TeamLogic;
use App\Logic\UserLogic;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;

class TeamController extends Controller
{
    public function __construct(
        protected TeamLogic $teamLogic,
        protected FileLogic $fileLogic,
        protected UserLogic $userLogic,
    ) {
    }

    public function createTeam(Request $request)
    {
        $request->validate([
            "team_name" => "required|min:5|max:20",
            "team_description" => "required|min:5|max:90",
            "team_pattern" => 'required',
        ]);

        $createdTeam = $this->teamLogic->createTeam(
            Auth::user()->id,
            $request->team_name,
            $request->team_description,
            $request->team_pattern,
        );

        return redirect()->route("viewTeam", ['team_id' => $createdTeam->id]);
    }

    public function updateData(Request $request)
    {
        $request->validate([
            "team_id" => "required|integer",
            "team_name" => "required|min:5|max:20",
            "team_description" => 'required|min:8|max:90',
            "team_pattern" => 'required',
        ]);
        $team_id = intval($request->team_id);
        $selectedTeam = Team::find($team_id);

        if ($selectedTeam == null) {
            return redirect()->back()->withErrors("This team is alredy deleted please contact team owner");
        }

        $selectedTeam->name = $request->team_name;
        $selectedTeam->description = $request->team_description;
        $selectedTeam->pattern = $request->team_pattern;
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
            ->with("patterns", TeamLogic::PATTERN)
            ->with("invites", $invites);
    }

    public function showTeam($team_id)
    {
        $team_id = intval($team_id);
        $user_id = Auth::user()->id;

        if (!$this->teamLogic->userHasAccsess($user_id, $team_id)) {
            return redirect()->back()->with('notif', ["You don't have access for that team, please try again or cantact the owner."]);
        }

        $selected_team = Team::find($team_id);
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);
        $team_members = $this->teamLogic->getTeamMember($selected_team->id);
        $team_boards = $this->teamLogic->getBoards($selected_team->id);

        return view("team")
            ->with("team", $selected_team)
            ->with("owner", $team_owner)
            ->with("members", $team_members)
            ->with("patterns", TeamLogic::PATTERN)
            ->with("backgrounds", BoardLogic::PATTERN)
            ->with("boards", $team_boards);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), ["team_name" => "required"]);
        if ($validator->fails()) {
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

    public function searchBoard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "team_id" => "required|integer",
            "user_id" => "required|integer",
            "board_name" => "required",
        ]);

        $team_id = intval($request->team_id);
        $user_id = Auth::user()->id;

        if (!$this->teamLogic->userHasAccsess($user_id, $team_id)) {
            return redirect()->route("home")->with('notif', ["You don't have access for that team, please try again or cantact the owner."]);
        }

        if ($validator->fails()) {
            return redirect()->route("viewTeam", ["team_id" => intval($request->team_id)]);
        }

        $request->session()->flash("__old_board_name", $request->board_name);
        $team_id = intval($request->team_id);
        $selected_team = Team::find($team_id);
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);
        $team_members = $this->teamLogic->getTeamMember($selected_team->id);
        $team_boards = $this->teamLogic->getBoards($selected_team->id, $request->board_name);

        return view("team")
            ->with("team", $selected_team)
            ->with("owner", $team_owner)
            ->with("members", $team_members)
            ->with("patterns", TeamLogic::PATTERN)
            ->with("backgrounds", BoardLogic::PATTERN)
            ->with("boards", $team_boards);
    }

    public function getInvite($user_id, $team_id)
    {
        $user_id = intval($user_id);
        $team_id = intval($team_id);

        $owner = $this->teamLogic->getTeamOwner($team_id);
        $team = Team::find($team_id);
        $owner_initials = $this->userLogic->getInitials($owner->name);
        $team_initials = $this->userLogic->getInitials($team->name);

        return response()->json([
            "owner_name" => $owner->name,
            "owner_initial" => $owner_initials,
            "owner_image" => $owner->image_path,
            "team_name" => $team->name,
            "team_initial" => $team_initials,
            "team_description" => $team->description,
            "team_image" => $team->image_path,
            "team_pattern" => $team->pattern,
            "accept_url" => route('acceptTeamInvite', ["user_id" => $user_id, "team_id" => $team_id]),
            "reject_url" => route('rejectTeamInvite', ["user_id" => $user_id, "team_id" => $team_id]),
        ]);
    }

    public function acceptInvite($user_id, $team_id)
    {
        $user_id = intval($user_id);
        $team_id = intval($team_id);

        $userInvite = UserTeam::all()
            ->where("user_id", $user_id)
            ->where("team_id", $team_id)
            ->first();

        if ($userInvite == null) {
            return redirect()->back()->with("notif", ["Error\nThe invite not found, it is either canceled or expired contacet the team owner."]);
        }

        $userInvite->status = "Member";
        $userInvite->save();

        return redirect()->back()->with("notif", ["Success\nInvite is accepted"]);
    }

    public function rejectInvite($user_id, $team_id)
    {
        $user_id = intval($user_id);
        $team_id = intval($team_id);

        $userInvite = UserTeam::all()
            ->where("user_id", $user_id)
            ->where("team_id", $team_id)
            ->first();

        if ($userInvite == null) {
            return redirect()->back();
        }

        $userInvite->delete();

        return redirect()->back()->with("notif", ["Success\nInvite is rejected"]);
    }

    public function deleteMembers(Request $request)
    {
        $team_id = intval($request->team_id);
        $this->teamLogic->deleteMembers($team_id, $request->emails);
        return response()->json(["message" => "delete success"]);
    }

    public function inviteMembers(Request $request)
    {
        $emails = $request->emails;
        $team_id = intval($request->team_id);

        if ($emails == null)
            return redirect()->back();


        foreach ($emails as $email) {
            $user = User::where("email", $email)->first();
            if ($user == null) continue;
            $existingInvite = UserTeam::where('user_id', $user->id)
                ->where('team_id', $team_id)
                ->first();

            if ($existingInvite != null) continue;

            UserTeam::create([
                "user_id" => $user->id,
                "team_id" => $team_id,
                "status" => "Pending"
            ]);
        }

        return redirect()->back()->with('notif', ["Success\nInvite sent, please wait."]);
    }

    public function deleteTeam(Request $request)
    {
        $request->validate([
            "team_id" => "required"
        ]);

        return redirect()->route("home")->with("notif", ["Deleted\nTeam deleted successfully"]);
    }

    public function leaveTeam(Request $request)
    {
        $request->validate([
            "team_id" => "required",
        ]);

        $user_email  = Auth::user()->email;
        $team_id = intval($request->team_id);

        $this->teamLogic->deleteMembers($team_id, [$user_email]);

        return redirect()->route("home")->with("notif", ["Leave\nSuccessfully left team..."]);
    }
}
