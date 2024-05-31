<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('id_ubigeo')->unique();
            $table->string('nombre_ubigeo');
            $table->string('codigo_ubigeo');
            $table->string('etiqueta_ubigeo');
            $table->string('buscador_ubigeo');
            $table->integer('numero_hijos_ubigeo');
            $table->integer('nivel_ubigeo');
            $table->string('id_padre_ubigeo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};
