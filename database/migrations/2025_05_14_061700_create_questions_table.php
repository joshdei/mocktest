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
    $table->unsignedBigInteger('course_id');
    $table->text('question');
    $table->string('question_type')->default('mcq');
    $table->string('option_a')->nullable();
    $table->string('option_b')->nullable();
    $table->string('option_c')->nullable();
    $table->string('option_d')->nullable();
    $table->string('answer');
    $table->string('status')->default('active');  // <-- Fixed typo here
    $table->timestamps();

    $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
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