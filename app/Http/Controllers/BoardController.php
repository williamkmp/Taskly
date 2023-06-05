<?php

namespace App\Http\Controllers;

use App\Logic\BoardLogic;
use App\Logic\TeamLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function __construct(
        protected TeamLogic $teamLogic,
        protected BoardLogic $boardLogic
    ) {
    }

    public function createBoard(Request $request)
    {
        $request->validate([
            "team_id" => "required",
            "board_name" => "required",
        ]);
        $user_id = Auth::user()->id;
        $team_id = intval($request->team_id);

        if (!$this->teamLogic->userHasAccsess($user_id, $team_id))
            return redirect()->route("home")->with('notif', ["You don't have access for that team, please try again or cantact the owner."]);

        $createdBoard = $this->boardLogic->createBoard($team_id, $request->board_name);

        if ($createdBoard == null)
            return redirect()->back()->with("notif", ["Error\nFail to create board, please try again"]);

        return redirect()->back()->with("notif", ["Success\nBoard created successfully!"]);
    }
}
