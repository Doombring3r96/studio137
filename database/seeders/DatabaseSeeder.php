<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1️⃣ Crear los roles
        $roles = [
            ['id' => 1, 'name' => 'Desarrollador', 'description' => 'Acceso total a todo el sistema'],
            ['id' => 2, 'name' => 'CEO', 'description' => 'Jefe de todos los demás'],
            ['id' => 3, 'name' => 'Director de Marca', 'description' => 'Dirige al Community Manager'],
            ['id' => 4, 'name' => 'Director Creativo', 'description' => 'Dirige al Diseñador gráfico'],
            ['id' => 5, 'name' => 'Community Manager', 'description' => 'Gestiona redes sociales y campañas'],
            ['id' => 6, 'name' => 'Diseñador gráfico', 'description' => 'Diseña contenido creativo'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                ['name' => $role['name'], 'description' => $role['description']]
            );
        }

        // 2️⃣ Crear un usuario por cada rol
        $users = [
            ['name' => 'Desarrollador', 'email' => 'dev@example.com', 'role_id' => 1],
            ['name' => 'CEO', 'email' => 'ceo@example.com', 'role_id' => 2],
            ['name' => 'Director de Marca', 'email' => 'marca@example.com', 'role_id' => 3],
            ['name' => 'Director Creativo', 'email' => 'creativo@example.com', 'role_id' => 4],
            ['name' => 'Community Manager', 'email' => 'community@example.com', 'role_id' => 5],
            ['name' => 'Diseñador gráfico', 'email' => 'diseño@example.com', 'role_id' => 6],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password123'), // contraseña por defecto
                    'role_id' => $user['role_id'],
                ]
            );
        }
    }
}
