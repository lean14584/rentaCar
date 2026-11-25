<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Pantallas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pantallas = array_keys(Pantallas::LABELS);

        foreach ($pantallas as $pantalla) {
            Permission::firstOrCreate(['name' => $pantalla]);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions($pantallas);

        $user = User::query()->firstOrCreate(
            ['username' => 'admin'],
            ['name' => 'Administrador', 'email' => 'admin@rentacar.test', 'password' => 'admin1234']
        );
        $user->assignRole($admin);
    }
}
