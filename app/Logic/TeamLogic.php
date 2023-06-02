<?php

namespace App\Logic;

use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Database\Eloquent\Collection;

class TeamLogic
{
    /**
     * get all registered teams of a given user
     *
     * @param int $userId user id
     *
     * @return Collection<int, Team> team where user is a member
     */
    function getUserTeams(int $user_id)
    {
        $teams = UserTeam::all()
            ->where("user_id", $user_id)
            ->where("status", "Member");

        return $teams;
    }

    /**
     * get all team invite of a given user
     *
     * @param int $user_id user id
     *
     * @return Collection<int, Team> team invites
     */
    function getUserTeamInvites(int $user_id)
    {
        $teams = UserTeam::all()
            ->where("user_id", $user_id)
            ->where("status", "Pending");

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
