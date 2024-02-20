<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('sa_profiles')->insert([
            [
                'user_id' => 111,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'middle_initial' => 'A',
                'gender' => 'Male',
                'contact_number' => 639112233456,
                'birth_date' => '2000-01-01',
                'birth_place' => 'House',
                'course_program' => 'Computer Science',
            ],
            
        ]);
    }
}
