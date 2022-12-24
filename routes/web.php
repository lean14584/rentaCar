<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\MarcasController;
use App\Http\Livewire\AutosController;
use App\Http\Livewire\ProveedoresController;
use App\Http\Livewire\ProveedoresCtaCteController;
use App\Http\Livewire\ClientesController;
use App\Http\Livewire\ClientesCtaCteController;
use App\Http\Livewire\SegurosController;
use App\Http\Livewire\PolizasController;
use App\Http\Livewire\MantenimientosController;
use App\Http\Livewire\UsuariosController;
use App\Http\Livewire\AsignarController;
use App\Http\Livewire\PermisosController;
use App\Http\Livewire\RolesController;
use App\Http\Livewire\CajaController;
use App\Http\Livewire\ReportesController;
use App\Http\Livewire\SegurosPantallaController;
use App\Http\Livewire\RtoController;
use App\Http\Livewire\CadenasController;
use App\Http\Livewire\MatafuegosController;
use App\Http\Livewire\SillasController;
use App\Http\Livewire\RolesPermisosController;
use App\Http\Livewire\AlquilerController;
use App\Http\Livewire\AlquilerListadoController;
use App\Http\Livewire\CalendarRentalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('marcas', MarcasController::class);
Route::get('autos', AutosController::class);
Route::get('proveedores', ProveedoresController::class);
Route::get('proveedoresCtaCte/{param}/{name}', ProveedoresCtaCteController::class);
Route::get('clientes', ClientesController::class);
Route::get('clientesCtaCte/{param}/{name}', ClientesCtaCteController::class);
Route::get('seguros', SegurosController::class);
Route::get('polizas', PolizasController::class);
Route::get('gastos', MantenimientosController::class);
Route::get('usuarios', UsuariosController::class);
Route::get('asignar', AsignarController::class);
Route::get('permisos', PermisosController::class);
Route::get('roles', RolesController::class);
Route::get('caja', CajaController::class);
Route::get('reportes', ReportesController::class);
Route::get('pantalla', SegurosPantallaController::class);
Route::get('rto', RtoController::class);
Route::get('cadenas', CadenasController::class);
Route::get('matafuegos', MatafuegosController::class);
Route::get('sillas', SillasController::class);
Route::get('pantallaRoles', RolesPermisosController::class);
Route::get('alquiler', AlquilerController::class);
Route::get('alquilerListado', AlquilerListadoController::class);
Route::get('calendar-rental', CalendarRentalController::class)->name('calendar');
