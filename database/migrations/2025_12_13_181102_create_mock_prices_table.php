<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_prices', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('currency')->default('USD');
            $table->integer('duration')->nullable()->comment('Duration in days');
            $table->date('durationendday')->nullable()->comment('Expiry date');
            $table->text('description')->nullable();
             $table->tinyInteger('aistatus')->default(0)->comment('0 = Inactive, 1 = Active');
           
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_prices');
    }
};