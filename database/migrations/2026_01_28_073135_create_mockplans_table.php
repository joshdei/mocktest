<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('userid')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('plan_id')
                  ->constrained('mock_prices')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_plans');
    }
};
