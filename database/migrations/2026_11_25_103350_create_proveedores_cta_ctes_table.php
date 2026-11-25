<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cuenta corriente por proveedor. mantenimiento_id/rto_id identifican el
     * Cargo generado cuando un gasto se paga "Cta Cte" (antes se saltaba
     * silenciosamente, sin quedar registrado en ningún lado). Las filas de
     * tipo Pago se cargan a mano desde la pantalla de cta cte.
     */
    public function up(): void
    {
        Schema::create('proveedores_cta_ctes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->cascadeOnDelete();
            $table->string('tipo');
            $table->decimal('importe', 10, 2);
            $table->string('descripcion');
            $table->foreignId('mantenimiento_id')->nullable()->constrained('mantenimientos')->cascadeOnDelete();
            $table->foreignId('rto_id')->nullable()->constrained('rtos')->cascadeOnDelete();
            $table->string('metodo_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores_cta_ctes');
    }
};
