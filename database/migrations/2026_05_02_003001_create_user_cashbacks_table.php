<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_cashbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('mock_prices')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->default(5.00);
            $table->string('type')->default('daily_login'); // for future cashback types
            $table->timestamp('cashback_date')->useCurrent();
            $table->timestamps();

            // Prevent duplicate cashback on same day
            $table->unique(['user_id', 'cashback_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_cashbacks');
    }
};