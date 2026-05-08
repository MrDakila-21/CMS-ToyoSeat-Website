<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'TSPCAdmin',
            'email' => 'admin@toyoseat.com',
            'password' => 'Intern2026',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}