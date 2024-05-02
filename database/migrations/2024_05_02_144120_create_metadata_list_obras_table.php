<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Esta tabla contiene informacion sobre la lista total de obras. Esto sirve para poblar la base de datos
     * Con todas las obras en la region loreto. Además 
     */
    public function up(): void
    {
        Schema::create('metadata_list_obras', function (Blueprint $table) {
            $table->id();
            $table->integer('pages_size');
            $table->integer('total_rows');
            $table->integer('total_pages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadata_list_obras');
    }
};
