<?php

namespace App\Logic;

use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Database\Eloquent\Collection;

class TeamLogic
{
    public const PATTERN = [
        'isometric',
        'zig-zag',
        'zig-zag-flat',
        'wavy',
        'triangle',
        'triangle-2',
        'moon',
        'rect',
        'box',
        'polka',
        'polka-2',
        'paper',
        'line-bold-horizontal',
        'line-bold-vertical',
        'line-thin-diagonal',
    ];


    /**
     * get all registered teams of a given user
     *
     * @param int $user_id owner id
     * @param string $team_name team name
     * @param string $team_description team description
     *
     * @return Team created team
     */
    function createTeam(int $user_id, string $team_name, string $team_description)
    {
        $selected_pattern = TeamLogic::PATTERN[array_rand(TeamLogic::PATTERN)];
        $newTeam = Team::create([
            "name" => $team_name,
            "description" => $team_description,
            "pattern" => $selected_pattern
        ]);

        UserTeam::create([
            "user_id" => $user_id,
            "team_id" => $newTeam->id,
            "status" => "Owner"
        ]);

        return $newTeam;
    }

    /**
     * get all registered teams of a given user
     *
     * @param int $user_id user id
     *
     * @return Collection<int, Team> team where user is a member
     */
    function getUserTeams(int $user_id, $status = ["Member", "Owner", "Pending"], $team_name = "%")
    {
        $teams = User::find($user_id)->teams()
            ->wherePivotIn("status", $status)
            ->where("name", "LIKE", "%" . $team_name . "%")
            ->get();

        return $teams;
    }

    /**
     * change the user team access to member
     *
     * @param int $user_id user id
     * @param int $team_id team id
     */
    public function inviteAccept(int $user_id, int $team_id)
    {
        $teamStatus = UserTeam::where([
            "user_id", $user_id,
            "team_id", $team_id,
        ])->first();

        $teamStatus->status = "Member";
        return;
    }
}
