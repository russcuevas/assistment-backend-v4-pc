<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'default_id' => 1, 
                'fullname' => 'Russel Cuevas', 
                'gender' => 'Male', 
                'age' => 22, 
                'birthday' => '2001-12-26',
                'strand' => 'HUMSS',
                'password' => Hash::make('ub1234')
            ],
            [
                'default_id' => 2, 
                'fullname' => 'Russel Cuevas 2', 
                'gender' => 'Male', 
                'age' => 22, 
                'birthday' => '2001-12-26',
                'strand' => 'STEM',
                'password' => Hash::make('ub1234')
            ],
        ]);
    }
}
