<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::create([
            "name" => "Taskly",
            "pattern" => "zig-zag",
            "description" => "Team for the Taskly web application, class assingment project.",
        ]);

        Team::create([
            "name" => "Frawncis",
            "pattern" => "isometric",
            "description" => "Team for the Frwncis web application, lab assingment project.",
        ]);

        Team::create([
            "name" => "Adins",
            "pattern" => "wavy",
            "description" => "Team repository for Adicipta Inovasi mobile division group.",
        ]);

        Team::create([
            "name" => "Laravel",
            "pattern" => "circle",
            "description" => "Taskly team for opn source Laravel application.",
        ]);
    }
}
