<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'is_admin' => true,
        ]);
        User::create([
            'name' => 'client',
            'username' => 'client',
            'first_name' => 'Client',
            'last_name' => 'User',
            'email' => 'client@example.com',
            'password' => Hash::make('123456'),
            'is_admin' => false,
        ]);
    }
} 