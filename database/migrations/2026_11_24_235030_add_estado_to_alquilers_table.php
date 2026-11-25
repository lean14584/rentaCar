<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La app vieja no trackeaba si un alquiler seguía activo o ya se
     * devolvió el auto — no había forma de saber, consultando solo la
     * tabla, si un alquiler viejo "terminó" o si alguien se olvidó de
     * poner el auto en Disponible de nuevo.
     */
    public function up(): void
    {
        Schema::table('alquilers', function (Blueprint $table) {
            $table->string('estado')->default('Activo')->after('silla_id');
        });
    }

    public function down(): void
    {
        Schema::table('alquilers', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
