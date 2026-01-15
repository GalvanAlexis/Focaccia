<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $vendedorRole = Role::create(['name' => 'vendedor']);
        $clienteRole = Role::create(['name' => 'cliente']);

        // Crear usuario administrador por defecto
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@labartola.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Asignar rol de admin
        $admin->assignRole('admin');

        echo "Roles y usuario admin creados exitosamente.\n";
        echo "Email: admin@labartola.com\n";
        echo "Password: admin123\n";
    }
}
