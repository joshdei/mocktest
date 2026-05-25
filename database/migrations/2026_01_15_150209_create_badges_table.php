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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Gold Member", "Top Performer"
            $table->string('icon')->nullable(); // Icon class or image path
            $table->string('color')->default('#FFD700'); // Badge color
            $table->text('description')->nullable(); // Badge description
            $table->enum('type', ['plan', 'achievement', 'special'])->default('achievement');
            $table->string('plan_type')->nullable(); // If type is 'plan', e.g., 'gold', 'silver'
            $table->integer('required_tests')->default(0); // Tests needed to earn
            $table->integer('required_score')->default(0); // Minimum score percentage
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0); // Display order
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
