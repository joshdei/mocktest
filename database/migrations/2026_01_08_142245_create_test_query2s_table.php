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
        Schema::create('test_query2s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained('mock_prices')->onDelete('set null');
            $table->integer('number_of_questions')->default(0);
            $table->json('exam_question_ids')->nullable(); // Store which questions were in exam
            $table->timestamps();
            
            // Index for better performance
            $table->index(['test_id', 'plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_query2s');
    }
};