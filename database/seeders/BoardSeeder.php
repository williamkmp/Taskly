<?php

namespace Database\Seeders;

use App\Logic\BoardLogic;
use App\Models\Board;
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
        $teams = Team::all();
        foreach($teams as $team){
            foreach($boardList as $board_name){
                $board = Board::create([
                    "team_id" => $team->id,
                    "name" => $board_name,
                    "pattern" => BoardLogic::PATTERN[array_rand(BoardLogic::PATTERN)],
                ]);
            }
        }

    }
}
