<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Una fila por turno de caja. monto_esperado/diferencia se calculan y
     * guardan recién al cerrar (solo sobre movimientos en Efectivo de la
     * sesión — lo único que es plata física contable).
     */
    public function up(): void
    {
        Schema::create('caja_sesiones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('usuario_apertura_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('usuario_cierre_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('monto_apertura', 10, 2);
            $table->decimal('monto_cierre', 10, 2)->nullable();
            $table->decimal('monto_esperado', 10, 2)->nullable();
            $table->decimal('diferencia', 10, 2)->nullable();
            $table->timestamp('hora_apertura');
            $table->timestamp('hora_cierre')->nullable();
            $table->string('estado')->default('Abierta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caja_sesiones');
    }
};
