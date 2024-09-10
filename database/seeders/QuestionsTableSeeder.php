<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questions')->insert([
            ['question_text' => 'What is 1 + 1?', 'question_subject' => 'Math'],
            ['question_text' => 'What is a meteor?', 'question_subject' => 'Science'],
        ]);
    }
}
