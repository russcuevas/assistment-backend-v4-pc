<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            ['question_id' => 1, 'option_text' => '2', 'is_correct' => true],
            ['question_id' => 1, 'option_text' => '3', 'is_correct' => false],
            ['question_id' => 1, 'option_text' => '4', 'is_correct' => false],
            ['question_id' => 1, 'option_text' => '5', 'is_correct' => false],
            ['question_id' => 2, 'option_text' => 'A machine', 'is_correct' => true],
            ['question_id' => 2, 'option_text' => 'A book', 'is_correct' => false],
            ['question_id' => 2, 'option_text' => 'A fruit', 'is_correct' => false],
            ['question_id' => 2, 'option_text' => 'A place', 'is_correct' => false],
        ]);
    }
}
