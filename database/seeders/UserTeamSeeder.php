<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emailMap = [
            "Taskly" => [
                "william@email.com",
                "sevien@email.com",
                "daffa@email.com",
                "farhan@email.com",
                "test@email.com",
            ],
            "Frwncis" => [
                "william@email.com",
                "daffa@email.com",
                "test@email.com"
            ],
            "Adins" => [
                "william@email.com",
                "filipus@email.com ",
                "owen@email.com",
                "test@email.com",
            ],
        ];

        $allTeam = Team::all();
        foreach ($allTeam as $team) {
            $emailList = $emailMap[$team->name];
            if ($emailList == null) continue;
            foreach ($emailList as $userEmail) {
                $user = User::where("email", $userEmail)->first();
                if ($user == null) continue;
                UserTeam::create([
                    "user_id" => $user->id,
                    "team_id" => $team->id,
                    "status" => "Member"
                ]);
            }
        }

        $william = User::where("email", "william@email.com")->first();
        $william->teamRelations()->update(["status" => "Owner"]);
    }
}
