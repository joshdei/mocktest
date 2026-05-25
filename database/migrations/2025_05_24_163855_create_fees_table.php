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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('faculty');
            $table->string('programme');
            $table->string('level');
            $table->string('semester');
            $table->string('course_code');
            $table->string('title');
            $table->string('unit');
            $table->string('status');
            $table->decimal('course_fee', 10, 2);
           $table->decimal('exam_fee', 10, 2)->nullable();
            $table->string('course_material')->nullable();
             $table->string('fee_status')->default('active');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};