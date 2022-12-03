<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alquileres', function (Blueprint $table) {
            $table->id();
			$table->integer('idAuto');
			$table->integer('idCliente');
			$table->integer('idCadena');
			$table->integer('idMatafuego');
			$table->integer('idSilla');
			$table->string('fechaInicio');
			$table->string('fechaFin');
			$table->string('lugar_retiro');
			$table->string('lugar_devolucion');
			$table->string('metodo_pago');
			$table->decimal('total',10,2);
			$table->decimal('entrega',10,2);
			$table->decimal('saldo',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alquileres');
    }
};
