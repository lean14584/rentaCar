<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Alquileres\Index as AlquilerIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Autos\Index as AutoIndex;
use App\Livewire\Caja\Index as CajaIndex;
use App\Livewire\Calendario\Index as CalendarioIndex;
use App\Livewire\Clientes\CtaCte as ClienteCtaCte;
use App\Livewire\Clientes\Index as ClienteIndex;
use App\Livewire\Dashboard;
use App\Livewire\Gastos\Mantenimientos\Index as MantenimientosIndex;
use App\Livewire\Gastos\Polizas\Index as PolizasIndex;
use App\Livewire\Gastos\Rto\Index as RtoIndex;
use App\Livewire\Gastos\Seguros\Index as SegurosIndex;
use App\Livewire\Marcas\Index as MarcaIndex;
use App\Livewire\Proveedores\CtaCte as ProveedorCtaCte;
use App\Livewire\Proveedores\Index as ProveedorIndex;
use App\Livewire\Reportes\Index as ReportesIndex;
use App\Livewire\Roles\Index as RolesIndex;
use App\Livewire\Usuarios\Index as UsuariosIndex;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->middleware('guest')->name('login');
Route::post('/logout', LogoutController::class)->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::middleware('can:marcas')->get('marcas', MarcaIndex::class)->name('marcas.index');
    Route::middleware('can:autos')->get('autos', AutoIndex::class)->name('autos.index');
    Route::middleware('can:clientes')->get('clientes', ClienteIndex::class)->name('clientes.index');
    Route::middleware('can:clientes')->get('clientes/{cliente}/cta-cte', ClienteCtaCte::class)->name('clientes.cta-cte');
    Route::middleware('can:proveedores')->get('proveedores', ProveedorIndex::class)->name('proveedores.index');
    Route::middleware('can:proveedores')->get('proveedores/{proveedor}/cta-cte', ProveedorCtaCte::class)->name('proveedores.cta-cte');
    Route::middleware('can:alquileres')->get('alquileres', AlquilerIndex::class)->name('alquileres.index');
    Route::middleware('can:calendar-rental')->get('calendario', CalendarioIndex::class)->name('calendar-rental');

    Route::middleware('can:gastos')->prefix('gastos')->name('gastos.')->group(function () {
        Route::get('seguros', SegurosIndex::class)->name('seguros');
        Route::get('polizas', PolizasIndex::class)->name('polizas');
        Route::get('mantenimientos', MantenimientosIndex::class)->name('mantenimientos');
        Route::get('rto', RtoIndex::class)->name('rto');
    });

    Route::middleware('can:caja')->get('caja', CajaIndex::class)->name('caja.index');
    Route::middleware('can:reportes')->get('reportes', ReportesIndex::class)->name('reportes.index');

    Route::middleware('can:usuarios')->get('usuarios', UsuariosIndex::class)->name('usuarios.index');
    Route::middleware('can:roles-permisos')->get('roles-permisos', RolesIndex::class)->name('roles-permisos.index');
});
