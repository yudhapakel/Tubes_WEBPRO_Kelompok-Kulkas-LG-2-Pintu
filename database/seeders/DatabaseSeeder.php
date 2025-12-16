<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Keuangan',
            'email' => 'admin@triloka.com',
            'role' => 'admin',
            'password' => bcrypt('password123'), 
        ]);

        User::create([
            'name' => 'Ujang Klien',
            'email' => 'klien@triloka.com',
            'role' => 'klien',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Pak Auditor',
            'email' => 'auditor@triloka.com',
            'role' => 'auditor',
            'password' => bcrypt('password123'),
        ]);
    }
}