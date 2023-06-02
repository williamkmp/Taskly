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
            "description" => "Team for the Taskly web application, class assingment project.",
        ]);

        Team::create([
            "name" => "Frwncis",
            "description" => "Team for the Frwncis web application, lab assingment project.",
        ]);

        Team::create([
            "name" => "Adins",
            "description" => "Team repository for Adicipta Inovasi group.",
        ]);
    }
}
