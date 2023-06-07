<?php

namespace App\Logic;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;

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

    public function hasAccess(int $user_id, int $board_id)
    {
        $board = Board::find($board_id);
        if ($board == null) return false;

        $team = Team::find($board->team_id);
        if ($team == null) return false;

        $access =  UserTeam::where("user_id", $user_id)
            ->where("team_id", $team->id)
            ->whereNot("status", "Pending")
            ->first();

        return ($access != null);
    }


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

    public function addColumn(int $board_id, string $column_name)
    {
        $board = Board::find($board_id);

        if ($board == null) return null;

        $lastColumn = Column::where("board_id", $board->id)
            ->whereNull("next_id")
            ->first();

        $column = Column::create([
            "name" => $column_name,
            "board_id" => $board->id,
            "previous_id" => $lastColumn ? $lastColumn->id : null,
        ]);

        if ($lastColumn) {
            $lastColumn->next_id = $column->id;
            $lastColumn->save();
        }
        return $column;
    }

    public function addCard(int $column_id, string $card_name)
    {
        $lastCard = Card::where("column_id", $column_id)
            ->whereNull("next_id")
            ->first();

        $newCard = Card::create([
            "name" => $card_name,
            "column_id" => $column_id,
            "previous_id" => $lastCard ? $lastCard->id : null
        ]);

        if($lastCard){
            $lastCard->next_id = $newCard->id;
            $lastCard->save();
        }

        return $newCard;
    }

    public function getData(int $board_id)
    {
        $columns = collect();
        $board = Board::find($board_id);

        $column = Column::where("board_id", $board->id)
            ->whereNull('previous_id')
            ->first();

        while ($column) {
            $cards = collect();
            $card = Card::where("column_id", $column->id)
                ->whereNull('previous_id')
                ->first();
            while ($card) {
                $card->setHidden(['nextCard', "created_at", "updated_at", "column_id", "previous_id", "next_id"]);
                $cards->push($card);
                $card = $card->nextCard;
            }
            $column->setHidden(['nextColumn', 'updated_at', 'created_at', 'previous_id', "next_id", "board_id"]);
            $column->cards = $cards->values();
            $columns->push($column);
            $column = $column->nextColumn;
        }
        $board->columns = $columns->values();
        $board->setHidden(["team_id", "image_path", "created_at", "updated_at", "pattern"]);
        return $board;
    }

    public function moveCard(int $target_card_id, int $column_id, int $bottom_card_id, int $top_card_id)
    {
        $column = Column::find($column_id);
        $target_card = Card::find($target_card_id);
        $previous_top_card = null;
        $previous_bottom_card = null;
        $top_card = null;
        $bottom_card = null;

        if($column == null) return null;
        if($bottom_card_id != 0) $bottom_card = Card::find($bottom_card_id);
        if($bottom_card != null) $top_card = Card::find($bottom_card->previous_id);
        if($target_card->previous_id) $previous_top_card = Card::find($target_card->previous_id);
        if($target_card->next_id) $previous_bottom_card = Card::find($target_card->next_id);
        if($bottom_card == null && $top_card == null) $top_card = Card::find($top_card_id);

        //insert in middle
        $target_card->column_id = $column->id;
        $target_card->previous_id = null;
        $target_card->next_id = null;
        if($previous_bottom_card){
            $previous_bottom_card->previous_id = $previous_top_card ? $previous_top_card->id : null;
        }
        if($previous_top_card){
            $previous_top_card->next_id = $previous_bottom_card ? $previous_bottom_card->id : null;
        }
        if($bottom_card){
            $target_card->next_id = $bottom_card->id;
            $bottom_card->previous_id = $target_card->id;
        }
        if($top_card){
            $target_card->previous_id = $top_card->id;
            $top_card->next_id = $target_card->id;
        }

        $target_card->save();
        if($bottom_card) $bottom_card->save();
        if($top_card) $top_card->save();
        if($previous_bottom_card) $previous_bottom_card->save();
        if($previous_top_card) $previous_top_card->save();
        return $target_card;
    }
}
