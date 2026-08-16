<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'questions_answered',
        'correct_streak',
        'total_correct',
        'bonus_questions',
        'has_won_today',
    ];

    protected $casts = [
        'date' => 'date',
        'has_won_today' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questionsAvailable()
    {
        $dailyQuestions = (int) GameConfig::get('daily_questions', 5);
        $totalAvailable = $dailyQuestions + $this->bonus_questions;
        return max(0, $totalAvailable - $this->questions_answered);
    }

    public function canPlay()
    {
        return $this->questionsAvailable() > 0;
    }

    public function hasStreak()
    {
        $streakRequired = (int) GameConfig::get('streak_required', 3);
        return $this->correct_streak >= $streakRequired;
    }
}
