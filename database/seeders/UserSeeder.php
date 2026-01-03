<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Almas',
            'email' => 'admin@mail.com',
            'phone' => '08123456789',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Karyawan',
            'email' => 'karyawan@mail.com',
            'phone' => '08129876543',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@mail.com',
            'phone'=> '081298765430',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
