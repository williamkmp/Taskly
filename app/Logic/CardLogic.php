<?php

namespace App\Logic;

use App\Models\Card;
use App\Models\CardHistory;
use App\Models\CardUser;

class CardLogic
{
    public function getData(int $card_id) {
        $card = Card::find($card_id);
        return $card;
    }

    public function getWorkers(int $card_id) {
        $users = Card::find($card_id)->users()->get();
        return $users;
    }

    public function addUser(int $card_id, int $user_id) {
        CardUser::create([
            "user_id" => $user_id,
            "card_id" => $card_id,
        ]);
        return;
    }

    public function removeUser(int $card_id, int $user_id) {
        CardUser::where([
            "user_id" => $user_id,
            "card_id" => $card_id,
        ])->delete();
        return;
    }

    function cardAddEvent(int $card_id, int $user_id, string $content){
        $event = CardHistory::create([
            "user_id" => $user_id,
            "card_id" => $user_id,
            "type" => "event",
            "content" => $content,
        ]);

        return $event;
    }

    function cardComment(int $card_id, int $user_id, string $content){
        $event = CardHistory::create([
            "user_id" => $user_id,
            "card_id" => $user_id,
            "type" => "comment",
            "content" => $content,
        ]);

        return $event;
    }

    function getHistories(int $card_id){
        $evets = CardHistory::with("user")
            ->where("card_id", $card_id)
            ->orderBy("created_at")
            ->get();
        return $evets;
    }
}
