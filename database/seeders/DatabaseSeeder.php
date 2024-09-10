<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminTableSeeder::class,
            UsersTableSeeder::class,
            CoursesTableSeeder::class,
            ChosenCourseTableSeeder::class,
            QuestionsTableSeeder::class,
            OptionsTableSeeder::class,
            CourseScoresTableSeeder::class,
            QuestionCourseTableSeeder::class,
            ResponsesTableSeeder::class,
        ]);
    }
}
