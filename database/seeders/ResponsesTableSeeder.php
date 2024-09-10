<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('responses')->insert([
            ['user_id' => 1, 'question_id' => 1, 'selected_option_id' => 1],
            ['user_id' => 1, 'question_id' => 2, 'selected_option_id' => 5],
            ['user_id' => 2, 'question_id' => 1, 'selected_option_id' => 2],
            ['user_id' => 2, 'question_id' => 2, 'selected_option_id' => 6],
        ]);
    }
}
