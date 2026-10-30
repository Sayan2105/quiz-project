<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{


    protected $fillable = ['status', 'difficulty', 'mode', 'score', 'completed_at', 'started_at'];
    public function attemptQuestions(): HasMany
    {
        return $this->hasMany(AttemptQuestion::class);
    }
}
