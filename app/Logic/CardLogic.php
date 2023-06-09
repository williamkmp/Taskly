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
            "card_id" => $card_id,
            "type" => "event",
            "content" => $content,
        ]);

        return $event;
    }

    function cardComment(int $card_id, int $user_id, string $content){
        $event = CardHistory::create([
            "user_id" => $user_id,
            "card_id" => $card_id,
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

    function deleteCard(int $target_card_id){
        $target_card = Card::find($target_card_id);
        $top_card = null;
        $bottom_card = null;
        if(!$target_card) return;
        if($target_card->previous_id) $top_card = Card::find($target_card->previous_id);
        if($target_card->next_id) $bottom_card = Card::find($target_card->next_id);

        if($top_card){
            $top_card->next_id = $bottom_card ? $bottom_card->id : null;
            $top_card->save();
        }
        if($bottom_card){
            $bottom_card->previous_id = $top_card ? $top_card->id : null;
            $bottom_card->save();
        }
        $target_card->delete();
    }
}
