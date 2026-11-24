<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla nueva: en la app vieja existían el modelo Proveedor y la
     * pantalla Livewire, pero nunca se creó esta tabla en la base real
     * (ni la migración, ni la tabla en MySQL) — la pantalla de Proveedores
     * no funcionaba. Esquema inferido de Proveedor::$fillable.
     */
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('inicio')->nullable();
            $table->string('cuit')->nullable();
            $table->string('brutos')->nullable();
            $table->string('localidad')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('correo')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
