<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nombre' => 'Test User',
            'correo' => 'test@example.com',
            'es_admin' => false,
        ]);

        User::factory()->create([
            'nombre' => 'Administrador',
            'correo' => 'admin@example.com',
            'clave' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'es_admin' => true,
        ]);
    }
}
