<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuário ativo
        User::create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => Hash::make('123456'),
            'status' => 'active',
        ]);

        // Usuário inativo
        User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'password' => Hash::make('123456'),
            'status' => 'inactive',
        ]);

        // Usuário ativo
        User::create([
            'name' => 'Pedro Oliveira',
            'email' => 'pedro@example.com',
            'password' => Hash::make('123456'),
            'status' => 'active',
        ]);
    }
}
