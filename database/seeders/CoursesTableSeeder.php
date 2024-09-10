<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('available_courses')->insert([
            ['course' => 'Bachelor of Science in Information Technology'],
            ['course' => 'Bachelor of Science in Business Administration'],
            ['course' => 'Bachelor of Science in Accountancy'],
            ['course' => 'Bachelor of Science in Education'],
        ]);
    }
}
