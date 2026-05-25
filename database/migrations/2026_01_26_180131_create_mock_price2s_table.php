<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_price2s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->enum('plan_type', [
                'free','basic', 'primary', 'silver', 'gold', 
                'platinum', 'enterprise', 'premium', 'standard'
            ]);
            $table->integer('number_of_question');
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('plan_id')
                  ->references('id')
                  ->on('mock_prices')
                  ->onDelete('cascade');
                  
            // Ensure one-to-one relationship
            $table->unique('plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_price2s');
    }
};