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
        Schema::create('semester_fees', function (Blueprint $table) {
            $table->id();
            $table->decimal('semester_fee', 10, 2)->comment('Fee amount for the semester');
            $table->string('level')->comment('Level of the students (e.g., 100, 200, etc.)');
            $table->string('semester')->comment('Semester name (e.g., First Semester, Second Semester)');
            $table->string('student_type')->comment('Type of students (e.g., Undergraduate, Graduate, Master)');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_fees');
    }
};