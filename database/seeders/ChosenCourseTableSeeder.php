<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChosenCourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chosen_courses')->insert([
            [
                'user_id' => 1, 
                'course_1' => 1, 
                'course_2' => 2, 
                'course_3' => 3
            ],
            [
                'user_id' => 2, 
                'course_1' => 2, 
                'course_2' => 1, 
                'course_3' => 4
            ],
        ]);
    }
}
