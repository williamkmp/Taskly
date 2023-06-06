<?php

namespace App\Logic;

use App\Models\Board;
use App\Models\Column;
use App\Models\Team;
use App\Models\User;

class BoardLogic
{
    public const PATTERN = [
        'sunkist',
        'mini',
        'sha-la-la',
        'celestial',
        'dream',
        'blue',
        'purple',
        'ellegant',
        'jaipur',
        'mild',
        'sunset',
        'cosmic',
        'jupiter',
        'police',
        'morning'
    ];

    public function createBoard(int $team_id, string $board_name)
    {
        $team = Team::find($team_id);
        $teamExist = ($team != null);
        $pattern = BoardLogic::PATTERN[array_rand(BoardLogic::PATTERN)];
        if (!$teamExist) return null;

        $createdBoard = Board::create([
            "team_id" => $team->id,
            "name" => $board_name,
            "pattern" => $pattern
        ]);

        return $createdBoard;
    }

    public function getColumns(int $board_id){
        $board = Board::find($board_id);
        if($board == null)
            return null;
        $columns = $board->columns()->orderBy('previous_id')->get();
        return $columns;
    }

}
