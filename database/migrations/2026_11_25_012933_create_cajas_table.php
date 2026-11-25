<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La app vieja guardaba entrada/salida como dos columnas decimales
     * donde siempre una quedaba en 0 — acá un solo monto + tipo
     * (Ingreso/Egreso), igual de simple de sumar y sin la columna muerta.
     *
     * alquiler_id/mantenimiento_id/rto_id identifican los movimientos
     * generados automáticamente desde esos módulos (entrega de un alquiler,
     * pago de un mantenimiento o RTO) — se borran en cascada si se borra el
     * alquiler/gasto de origen, para que la caja no quede desincronizada.
     * Los movimientos cargados a mano no tienen ninguno de los tres.
     */
    public function up(): void
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('auto_id')->nullable()->constrained('autos')->nullOnDelete();
            $table->foreignId('alquiler_id')->nullable()->constrained('alquilers')->cascadeOnDelete();
            $table->foreignId('mantenimiento_id')->nullable()->constrained('mantenimientos')->cascadeOnDelete();
            $table->foreignId('rto_id')->nullable()->constrained('rtos')->cascadeOnDelete();
            $table->string('tipo');
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago');
            $table->string('descripcion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
