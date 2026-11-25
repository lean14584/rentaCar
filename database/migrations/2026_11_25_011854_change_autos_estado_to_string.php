<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * El enum original solo permitía Disponible/Alquilado/No Disponible —
     * Gastos necesita marcar un auto "En Mantenimiento" y cada módulo nuevo
     * que toque el estado del auto agregaría otro valor más. Se pasa a
     * string simple, igual que alquilers.estado y mantenimientos.estado.
     */
    public function up(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            $table->string('estado')->default('Disponible')->change();
        });
    }

    public function down(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            $table->enum('estado', ['Disponible', 'Alquilado', 'No Disponible'])->default('Disponible')->change();
        });
    }
};
