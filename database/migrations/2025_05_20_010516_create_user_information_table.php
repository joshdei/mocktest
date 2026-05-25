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
       Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('mat_number')->nullable();
            $table->string('username')->nullable();
            $table->string('zone')->nullable();
            $table->string('study_centre')->nullable();
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->string('programme')->nullable();
            $table->string('level')->nullable();
             $table->string('semester')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_information');
    }
};