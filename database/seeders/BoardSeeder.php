<?php

namespace Database\Seeders;

use App\Logic\BoardLogic;
use App\Models\Board;
use App\Models\Column;
use App\Models\Team;
use App\Models\TeamBoard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boardList = ["Development", "Testing", "Design"];

        $team = Team::where("name", "Taskly")->first();
        $board =  Board::create([
            "team_id" => $team->id,
            "name" => "Developemnt",
            "pattern" => BoardLogic::PATTERN[array_rand(BoardLogic::PATTERN)],
        ]);

        $col1 = Column::create([
            "name" => "To-Do",
            "board_id" => $board->id,
            "previous_id" => null,
            "next_id" => null,
        ]);

        $col2 = Column::create([
            "name" => "Development",
            "board_id" => $board->id,
            "previous_id" => $col1->id,
            "next_id" => null,
        ]);

        $col1->next_id = $col2->id;
        $col1->save();

        $col1 = Column::create([
            "name" => "Development",
            "board_id" => $board->id,
            "previous_id" => $col2->id,
            "next_id" => null,
        ]);

        $col2->next_id = $col1->id;
        $col2->save();
    }
}
