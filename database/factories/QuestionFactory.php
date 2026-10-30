<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'topic_id' => Topic::factory(),
            'difficulty' => 'easy',
            'question_text' => fake()->sentence() . '?',
            'options' => [
                'A' => 'Option A',
                'B' => 'Option B',
                'C' => 'Option C',
                'D' => 'Option D',
            ],
            'correct_answer' => 'A',
            'explanation' => fake()->sentence(),
            'source' => 'manual',
        ];
    }
}
