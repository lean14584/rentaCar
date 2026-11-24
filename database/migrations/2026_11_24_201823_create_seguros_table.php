<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('auto_id')->constrained('autos')->cascadeOnDelete();
            $table->decimal('valor', 10, 2);
            $table->date('vigencia_hasta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguros');
    }
};
