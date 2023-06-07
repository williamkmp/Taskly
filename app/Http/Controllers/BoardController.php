<?php

namespace App\Http\Controllers;

use App\Logic\BoardLogic;
use App\Logic\TeamLogic;
use App\Models\Board;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
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

    public function addColumn(Request $request, $team_id)
    {
        $request->validate([
            "board_id" => "required",
            "column_name" => "required",
        ]);
        $user_id = Auth::user()->id;
        $team_id = intval($team_id);
        $board_id = intval($request->board_id);

        if (!$this->teamLogic->userHasAccsess($user_id, $team_id))
            return redirect()->route("home")->with('notif', ["You don't have access for that team, please try again or cantact the owner."]);

        $createdColumn = $this->boardLogic->addColumn($board_id, $request->column_name);

        if ($createdColumn == null)
            return redirect()->back()->with("notif", ["Error\nFail to create board, please try again"]);

        return response()->json($createdColumn);
    }

    public function showBoard($board_id)
    {
        $board_id = intval($board_id);
        $board = $this->boardLogic->getData($board_id);
        $team = Team::find($board->team_id);
        $teamOwner = $this->teamLogic->getTeamOwner($board->team_id);

        return view("board")
            ->with("team", $team)
            ->with("owner", $teamOwner)
            ->with("board", $board)
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

    public function addCard(Request $request, $board_id, $column_id)
    {
        $board_id = intval($board_id);
        $user_id = Auth::user()->id;
        $column_id = intval($column_id);
        $card_name = $request->name;

        if(!$this->boardLogic->hasAccess($user_id, $board_id)){
            return response()->json(["url" => route("home")], HttpResponse::HTTP_BAD_REQUEST);
        }

        $newCard = $this->boardLogic->addCard($column_id, $card_name);
        return response()->json($newCard);
    }

    public function getData($board_id)
    {
        $boardData = $this->boardLogic->getData(intval($board_id));
        return response()->json($boardData);
    }

    public function reorderCard(Request $request, $board_id)
    {
        $user_id = Auth::user()->id;
        $board_id = intval($board_id);
        $column_id = intval($request->column_id);
        $middle_id = intval($request->middle_id);
        $bottom_id = intval($request->bottom_id);
        $top_id = intval($request->top_id);

        if(!$this->boardLogic->hasAccess($user_id, $board_id)){
            return response()->json(["url" => route("home")], HttpResponse::HTTP_BAD_REQUEST);
        }

        $updatedCard = $this->boardLogic->moveCard($middle_id, $column_id, $bottom_id, $top_id);

        return response()->json($updatedCard);
    }
}
