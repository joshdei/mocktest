<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_question_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->unsignedTinyInteger('week_number');
            $table->unsignedSmallInteger('year');
            $table->date('scheduled_date');
            $table->timestamps();

            $table->unique(['week_number', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_question_schedule');
    }
};

