<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "card_histories";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'card_id',
        'type',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
