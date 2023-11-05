<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
            [
                'id_number' => 110,
                'username' => 'office',
                'account_type' => 'office_admin',
                'email' => 'office@email.com',
                'password' => bcrypt('password'),
            ],
            [
                'id_number' => 111,
                'username' => 'studentassistant',
                'account_type' => 'student_assistant',
                'email' => 'student_assistant@email.com',
                'password' => bcrypt('password'),
            ],
            [
                'id_number' => 112,
                'username' => 'studentassistantmanager',
                'account_type' => 'sa_manager',
                'email' => 'student_assistant_manager@email.com',
                'password' => bcrypt('password'),
            ],
        ]);

    }
}
