<?php

namespace App\Http\Controllers;

use App\Logic\BoardLogic;
use App\Logic\TeamLogic;
use App\Models\Board;
use App\Models\Team;
use App\Models\User;
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

    public function showBoard($board_id)
    {
        $board_id = intval($board_id);
        $board = Board::find($board_id);
        $team = Team::find($board->team_id);
        $teamOwner = $this->teamLogic->getTeamOwner($board->team_id);
        $columns = $this->boardLogic->getColumns($board->id);

        return view("board")
            ->with("team", $team)
            ->with("owner", $teamOwner)
            ->with("board", $board)
            ->with("columns", $columns)
            ->with("patterns", BoardLogic::PATTERN);
    }

    public function updateBoard(Request $request)
    {
        $request->validate([
            "board_id" => "required",
            "board_name" => "required",
            "board_pattern" => "required",
        ]);

        $board = Board::find(intval($request->board_id));
        $board->name = $request->board_name;
        $board->pattern = $request->board_pattern;
        $board->save();

        return redirect()->back()->with("notif", ["Success\nBoard is successfully updated!"]);
    }
}
