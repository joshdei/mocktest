<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_challenges', function (Blueprint $table) {
            $table->id();

            $table->foreignId('challenger_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('opponent_id')->constrained('users')->cascadeOnDelete();

            $table->json('question_set');

            $table->unsignedInteger('challenger_score')->nullable();
            $table->unsignedInteger('opponent_score')->nullable();

            $table->string('status'); // pending, challenger_played, completed, declined, expired

            $table->timestamp('expires_at');

            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['challenger_id', 'status']);
            $table->index(['opponent_id', 'status']);
            $table->index(['expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_challenges');
    }
};
