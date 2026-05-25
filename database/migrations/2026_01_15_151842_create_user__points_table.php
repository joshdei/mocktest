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
       Schema::create('user__points', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id'); // Foreign key to users table
    $table->integer('bonus_points')->default(0); // For Gold plan bonus points
    $table->date('last_login_bonus_date')->nullable(); // Track last login bonus date
    $table->integer('total_points')->default(0); // Optional: Total points (bonus + others)
    $table->integer('used_points')->default(0); // Optional: Track used points
    $table->timestamps();

    // Foreign key constraint
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
    // Unique constraint to ensure one record per user
    $table->unique('user_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user__points');
    }
};