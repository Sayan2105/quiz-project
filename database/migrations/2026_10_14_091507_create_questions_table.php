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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('topic_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('difficulty', 20);
            $table->text('question_text');
            $table->jsonb('options');
            $table->string('correct_answer', 1);
            $table->text('explanation')->nullable();
            $table->string('source', 20)->default('manual');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
