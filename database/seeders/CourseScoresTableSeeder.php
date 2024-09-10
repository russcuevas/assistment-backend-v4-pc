<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseScoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('course_scores')->insert([
            ['user_id' => 1, 'course_id' => 1, 'points' => 2],
            ['user_id' => 1, 'course_id' => 2, 'points' => 1],
            ['user_id' => 1, 'course_id' => 3, 'points' => 1],
        ]);
    }
}
