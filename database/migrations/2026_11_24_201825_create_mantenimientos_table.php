<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La migración vieja solo tenía empresa/idAuto/valor, pero
     * Mantenimiento::$fillable ya declaraba muchos más campos
     * (km_actual, proveedor_id, km_proximo, estado, fechas, metodo_pago)
     * que nunca llegaron a tener columna real. Esquema completo acá.
     */
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('auto_id')->constrained('autos')->cascadeOnDelete();
            $table->unsignedInteger('km_actual')->nullable();
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();
            $table->unsignedInteger('km_proximo')->nullable();
            $table->decimal('valor', 10, 2);
            $table->string('estado')->default('Pendiente');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->unsignedInteger('dias_arreglo')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};
