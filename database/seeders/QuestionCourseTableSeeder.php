<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionCourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('question_courses')->insert([
            ['question_id' => 1, 'course_id' => 1],
            ['question_id' => 1, 'course_id' => 2],
            ['question_id' => 1, 'course_id' => 3],
            ['question_id' => 2, 'course_id' => 1],
        ]);
    }
}
