<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'achievement_id',
    ];

    /**
     * Get the user that earned the achievement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the achievement that was earned.
     */
    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
