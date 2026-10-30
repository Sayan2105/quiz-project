<?php

namespace Tests\Unit;

use App\Models\Question;
use App\Models\Topic;
use App\Services\QuizEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class QuizEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_rejects_invalid_difficulty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new QuizEngine())->selectQuestions([1], 'expert');
    }

    public function test_it_rejects_empty_topic_ids(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new QuizEngine())->selectQuestions([], 'easy');
    }

    public function test_it_rejects_fewer_than_eight_questions(): void
    {
        $topic = Topic::create([
            'name' => 'Technology',
            'slug' => 'technology',
        ]);

        Question::factory()->count(7)->create([
            'topic_id' => $topic->id,
            'difficulty' => 'easy',
        ]);

        $this->expectException(RuntimeException::class);

        (new QuizEngine())->selectQuestions([$topic->id], 'easy');
    }


    
    public function test_it_selects_eight_matching_questions(): void
    {
        $topic = Topic::factory()->create();

        Question::factory()->count(8)->create([
            'topic_id' => $topic->id,
            'difficulty' => 'easy',
        ]);

        $questions = (new QuizEngine())->selectQuestions(
            [$topic->id],
            'easy'
        );

        $this->assertCount(8, $questions);

        $this->assertTrue(
            $questions->every(
                fn (Question $question) =>
                    $question->topic_id === $topic->id
                    && $question->difficulty === 'easy'
            )
        );
    }

    
    public function test_it_creates_an_attempt_with_eight_questions(): void {
        $topic = Topic::factory()->create();

        Question::factory()->count(8)->create([
            'topic_id' => $topic->id,
            'difficulty' => 'medium',
        ]);

        $attempt = (new QuizEngine())->start(
            [$topic->id],
            'medium',
            'ai'
        );

        $this->assertDatabaseHas('quiz_attempts', [
            'id' => $attempt->id,
            'mode' => 'ai',
            'difficulty' => 'medium',
            'status' => 'in_progress',
            'score' => 0,
        ]);

        $this->assertCount(8, $attempt->attemptQuestions);

        $this->assertEquals(
            range(1, 8),
            $attempt->attemptQuestions
                ->pluck('position')
                ->sort()
                ->values()
                ->all()
        );
    }

    public function test_it_rejects_invalid_mode(): void {
        $this->expectException(\InvalidArgumentException::class);

        (new QuizEngine())->start([1], 'easy', 'tournament');
    }


}
