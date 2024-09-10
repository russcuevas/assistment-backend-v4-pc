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
        Schema::create('chosen_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_1');
            $table->unsignedBigInteger('course_2');
            $table->unsignedBigInteger('course_3');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_1')->references('id')->on('available_courses')->onDelete('cascade');
            $table->foreign('course_2')->references('id')->on('available_courses')->onDelete('cascade');
            $table->foreign('course_3')->references('id')->on('available_courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chosen_courses');
    }
};
