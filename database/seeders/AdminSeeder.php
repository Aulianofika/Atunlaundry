<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Atun',
            'email' => 'atun@atunlaundry.com',
            'phone' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('atun123'),
            'email_verified_at' => now(),
        ]);
    }
}
