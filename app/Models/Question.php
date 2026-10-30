<?php

namespace App\Models;
use app\Models\Topic;
use app\Models\AttemptQuestion ;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Question extends Model
{
    use HasFactory;
    //

    protected $fillable = ['topic_id', 'difficulty', 'question_text', 'options', 'correct_answer', 'explanation', 'source',];

    protected function casts(): array
        {
            return [
                'options' => 'array',
            ];
        }

    public function topic(): BelongsTo{
        return $this->belongsTo(Topic::class);
    }

    public function attemptQuestions(): hasMany{
        return $this->hasMany(AttemptQuestion::class);
    }
}
