<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polizas', function (Blueprint $table) {
            $table->id();
            $table->string('empresa');
            $table->foreignId('auto_id')->constrained('autos')->cascadeOnDelete();
            $table->date('vigencia_hasta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polizas');
    }
};
