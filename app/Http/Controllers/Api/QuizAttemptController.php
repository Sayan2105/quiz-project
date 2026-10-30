<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\QuizEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuizAttemptController extends Controller
{
    public function store( Request $request, QuizEngine $quizEngine ): JsonResponse {
        $validated = $request->validate([
            'topic_ids' => ['required', 'array', 'min:1'],
            'topic_ids.*' => [
                'required',
                'integer',
                'min:1',
                'distinct',
                'exists:topics,id',
            ],
            'difficulty' => [
                'required',
                Rule::in(['easy', 'medium', 'hard']),
            ],
            'mode' => [
                'required',
                Rule::in(['solo', 'multi']),
            ],
        ]);

        $attempt = $quizEngine->start(
            $validated['topic_ids'],
            $validated['difficulty'],
            $validated['mode'],
        );

        return response()->json([
            'id' => $attempt->id,
            'mode' => $attempt->mode,
            'difficulty' => $attempt->difficulty,
            'status' => $attempt->status,
            'questions' => $attempt->attemptQuestions->map(
                fn ($item) => [
                    'id' => $item->id,
                    'position' => $item->position,
                    'question' => $item->question->question_text,
                    'options' => $item->question->options,
                ]
            ),
        ], 201);
    }
}
