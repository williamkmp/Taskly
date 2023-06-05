<?php

namespace App\Logic;

use App\Models\Board;
use App\Models\Team;
use App\Models\User;

class BoardLogic
{
    public function __construct(protected TeamLogic $teamLogic)
    {
    }

    public function createBoard(int $team_id, string $board_name)
    {
        $team = Team::find($team_id);
        $teamExist = ($team != null);
        if (!$teamExist) return null;

        $createdBoard = Board::create([
            "team_id" => $team->id,
            "name" => $board_name,
            "pattern" => $team->pattern
        ]);

        return $createdBoard;
    }
}
