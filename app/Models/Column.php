<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'board_id',
        'previous_id',
        'next_id',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function previousColumn()
    {
        return $this->belongsTo(Column::class, 'previous_id');
    }

    public function nextColumn()
    {
        return $this->belongsTo(Column::class, 'next_id');
    }
}
