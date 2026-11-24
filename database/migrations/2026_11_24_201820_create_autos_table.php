<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autos', function (Blueprint $table) {
            $table->id();
            $table->string('patente')->unique();
            $table->enum('titular', ['Dueño', 'Tercero'])->default('Dueño');
            $table->unsignedInteger('kilometros')->default(0);
            // Kilometraje al que corresponde el próximo cambio de aceite — en
            // la app vieja este campo se usaba en validación/guardado pero la
            // columna nunca existió en la tabla real (creaba/editaba un auto
            // tiraba error SQL). Se agrega acá de verdad.
            $table->unsignedInteger('cambio_aceite')->nullable();
            $table->string('combustible');
            $table->unsignedSmallInteger('modelo');
            $table->foreignId('marca_id')->constrained('marcas')->restrictOnDelete();
            $table->string('tipo');
            $table->string('color');
            $table->enum('estado', ['Disponible', 'Alquilado', 'No Disponible'])->default('Disponible');
            $table->decimal('precio_alquiler', 10, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autos');
    }
};
