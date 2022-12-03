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
        Schema::create('autos', function (Blueprint $table) {
            $table->id();
			$table->string('patente');
			$table->enum('titular',['Dueño','Tercero'])->default('Dueño');
            $table->integer('kilometros');
			$table->integer('modelo');
			$table->integer('marca_id');
			$table->string('tipo');
			$table->string('color');
			$table->enum('estado',['Disponible','Alquilado','No Disponible'])->default('Disponible');
            $table->string('importe');
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
        Schema::dropIfExists('autos');
    }
};
