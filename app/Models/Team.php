<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'pattern',
        'image_path',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, "user_team", "team_id", "user_id")->withPivot("status");
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    public function userRelations()
    {
        return $this->hasMany(UserTeam::class);
    }
}
