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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('default_id')->unique();
            $table->string('fullname');
            $table->string('gender');
            $table->integer('age');
            $table->date('birthday');
            $table->string('strand');
            $table->string('course_1');
            $table->string('course_2');
            $table->string('course_3');
            $table->integer('total_points');
            $table->integer('number_of_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
