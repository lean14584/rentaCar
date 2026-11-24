<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alquilers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auto_id')->constrained('autos')->restrictOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('cadena_id')->nullable()->constrained('cadenas')->nullOnDelete();
            $table->foreignId('matafuego_id')->nullable()->constrained('matafuegos')->nullOnDelete();
            $table->foreignId('silla_id')->nullable()->constrained('sillas')->nullOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('lugar_retiro');
            $table->string('lugar_devolucion');
            $table->string('metodo_pago');
            $table->decimal('total', 10, 2);
            $table->decimal('entrega', 10, 2)->default(0);
            $table->decimal('saldo', 10, 2)->default(0);
            $table->unsignedInteger('dias_alquiler')->nullable();
            $table->decimal('importe_por_dia', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alquilers');
    }
};
