<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'name' => "Admin",
                'email' => "slamtm608@gmail.com",
                'password' => bcrypt('Moh@123456789'),
            ],
            [
                'name' => "Admin 2",
                'email' => "admin2@example.com",
                'password' => bcrypt('Admin2@123456'),
            ],
            [
                'name' => "Admin 3",
                'email' => "admin3@example.com",
                'password' => bcrypt('Admin3@123456'),
            ],
            // You can add more admin entries as needed
        ]);
    }
}
