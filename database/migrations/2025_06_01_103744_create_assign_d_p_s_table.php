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
        Schema::create('assign_d_p_s', function (Blueprint $table) {
            $table->id();
              $table->unsignedBigInteger('course_id');
        $table->unsignedBigInteger('programme_id');
     $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assign_d_p_s', function (Blueprint $table) {
            $table->dropUnique('assign_d_p_s_unique');
        });
    }
};