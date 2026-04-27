<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat 1 akun Admin utama secara otomatis
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        
        // (Opsional) Membuat 1 akun Evaluator percobaan
        User::create([
            'name' => 'Bapak Evaluator',
            'email' => 'evaluator@kampus.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'evaluator',
        ]);
    }
}
