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
        Schema::create('contrataciones_obra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obra_id')->constrained('obras');
            $table->json('contrataciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrataciones_obra', function (Blueprint $table){
            $table->dropConstrainedForeignId('obra_id');
        });
        Schema::dropIfExists('contrataciones_obra');
    }
};
