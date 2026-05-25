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
    Schema::create('siwes_students', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->string('pass_port')->nullable(); // Changed to nullable if file upload fails
        $table->string('matric_no')->unique(); // Added unique
        $table->string('year_of_study');
        $table->string('study_centre');
        $table->string('programme_id');
        $table->string('department_id');
        $table->string('level');
        $table->text('residential_address'); // Changed to text for longer addresses
        $table->date('assumption_date');
        $table->date('attachment_start_date');
        $table->date('to_date');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siwes_students');
    }
};
