<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attempt_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained()
                ->restrictOnDelete();

            $table->unsignedSmallInteger('position');

            $table->string('player_answer', 1)->nullable();

            $table->boolean('player_is_correct')->nullable();

            $table->timestamps();

            $table->unique(['quiz_attempt_id', 'position']);
            $table->unique(['quiz_attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_questions');
    }
};
