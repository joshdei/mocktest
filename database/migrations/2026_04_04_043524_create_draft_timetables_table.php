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
        Schema::create('draft_timetables', function (Blueprint $table) {
            $table->id();
            $table->string('exam_date');
            $table->string('time_slot');
            $table->string('type_of_time_table');
            $table->string('course_code');
            $table->string('course_title');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_timetables');
    }
};
