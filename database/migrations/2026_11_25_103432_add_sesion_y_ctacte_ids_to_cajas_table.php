<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cajas', function (Blueprint $table) {
            $table->foreignId('caja_sesion_id')->nullable()->after('id')->constrained('caja_sesiones')->nullOnDelete();
            $table->foreignId('cliente_cta_cte_id')->nullable()->after('rto_id')->constrained('clientes_cta_ctes')->cascadeOnDelete();
            $table->foreignId('proveedor_cta_cte_id')->nullable()->after('cliente_cta_cte_id')->constrained('proveedores_cta_ctes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cajas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('caja_sesion_id');
            $table->dropConstrainedForeignId('cliente_cta_cte_id');
            $table->dropConstrainedForeignId('proveedor_cta_cte_id');
        });
    }
};
