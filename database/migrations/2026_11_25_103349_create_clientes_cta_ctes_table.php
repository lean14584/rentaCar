<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cuenta corriente por cliente. alquiler_id identifica el Cargo que se
     * genera solo cuando un alquiler se guarda con saldo pendiente > 0 — se
     * borra en cascada si se borra el alquiler. Las filas de tipo Pago no
     * tienen alquiler_id (se cargan a mano desde la pantalla de cta cte).
     */
    public function up(): void
    {
        Schema::create('clientes_cta_ctes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('tipo');
            $table->decimal('importe', 10, 2);
            $table->string('descripcion');
            $table->foreignId('alquiler_id')->nullable()->constrained('alquilers')->cascadeOnDelete();
            $table->string('metodo_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes_cta_ctes');
    }
};
