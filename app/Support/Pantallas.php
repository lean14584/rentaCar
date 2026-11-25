<?php

namespace App\Support;

/**
 * Fuente única de las pantallas/permisos del sistema — un permiso por
 * pantalla del menú, mismo nombre que su route. La usan el seeder (para
 * crear los permisos) y el módulo Roles y permisos (para mostrar el
 * checklist con etiquetas legibles en vez del nombre técnico).
 */
class Pantallas
{
    public const LABELS = [
        'dashboard' => 'Dashboard',
        'marcas' => 'Marcas',
        'autos' => 'Autos',
        'alquileres' => 'Alquileres',
        'calendar-rental' => 'Calendario',
        'clientes' => 'Clientes',
        'proveedores' => 'Proveedores',
        'gastos' => 'Gastos',
        'caja' => 'Caja',
        'reportes' => 'Reportes',
        'usuarios' => 'Usuarios',
        'roles-permisos' => 'Roles y permisos',
    ];
}
