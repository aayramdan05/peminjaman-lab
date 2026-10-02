<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin PPBS',
            'email' => 'admin@unpad.ac.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);
        
        \App\Models\User::create([
            'name' => 'Operator PPBS',
            'email' => 'operator@unpad.ac.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'operator',
        ]);
        
        \App\Models\User::create([
            'name' => 'Mahasiswa',
            'email' => 'user@unpad.ac.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
