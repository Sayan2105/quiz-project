<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\DB;

class QuizEngine {
    public function selectQuestions(array $topicIds, string $difficulty ): Collection {

        $allowedDifficulties = ['easy', 'medium', 'hard'];

        if (! in_array($difficulty, $allowedDifficulties, true)) {
            throw new \InvalidArgumentException(
                'Invalid quiz difficulty.'
            );
        }

        if ($topicIds === []) {
            throw new \InvalidArgumentException(
                'Select at least one topic.'
            );
        } 

        $questions = Question::query()
            ->whereIn('topic_id', $topicIds)
            ->where('difficulty', $difficulty)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        if ($questions->count() !== 8) {
            throw new \RuntimeException(
                'Not enough questions available for this quiz.'
            );
        }

        return $questions;
    }
    
    public function start(array $topicIds, string $difficulty, string $mode): QuizAttempt {
        if (! in_array($mode, ['solo', 'multi'], true)) {
            throw new \InvalidArgumentException(
                'Invalid quiz mode.'
            );
        }

        return DB::transaction(function () use ($topicIds, $difficulty, $mode) {
            $questions = $this->selectQuestions(
                $topicIds,
                $difficulty
            );

            $attempt = QuizAttempt::create([
                'mode' => $mode,
                'difficulty' => $difficulty,
                'status' => 'in_progress',
                'score' => 0,
            ]);

            foreach ($questions as $index => $question) {
                $attempt->attemptQuestions()->create([
                    'question_id' => $question->id,
                    'position' => $index + 1,
                ]);
            }

            return $attempt->load('attemptQuestions.question');
        });
    }

}
