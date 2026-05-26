<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_question_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('weekly_question_schedule_id')->constrained('weekly_question_schedule')->onDelete('cascade');
            $table->unsignedTinyInteger('week_number');
            $table->unsignedSmallInteger('year');
            $table->string('selected_option');
            $table->boolean('is_correct');
            $table->timestamps();

            $table->unique(['user_id', 'week_number', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_question_attempts');
    }
};

