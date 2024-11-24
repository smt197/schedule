<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Premier utilisateur (sender)
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'balance' => 1000.00, // Solde initial
        ]);

        // Deuxième utilisateur (receiver)
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'balance' => 500.00, // Solde initial
        ]);

        // Génération de quelques utilisateurs supplémentaires
        User::factory()->count(8)->create([
            'balance' => 1000.00,
        ]);
    }
}