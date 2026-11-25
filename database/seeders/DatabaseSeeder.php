<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Un permiso por pantalla del menú (mismo nombre que la route), igual
     * que la app vieja permitía armar roles a medida desde Roles/Permisos
     * — acá se arranca con un set fijo de pantallas conocidas; nuevas
     * pantallas futuras agregan su permiso acá.
     */
    private const PANTALLAS = [
        'dashboard', 'marcas', 'autos', 'alquileres', 'calendar-rental',
        'clientes', 'proveedores', 'gastos', 'caja', 'reportes', 'usuarios', 'roles-permisos',
    ];

    public function run(): void
    {
        foreach (self::PANTALLAS as $pantalla) {
            Permission::firstOrCreate(['name' => $pantalla]);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions(self::PANTALLAS);

        $user = User::query()->firstOrCreate(
            ['username' => 'admin'],
            ['name' => 'Administrador', 'email' => 'admin@rentacar.test', 'password' => 'admin1234']
        );
        $user->assignRole($admin);
    }
}
