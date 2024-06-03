<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    /***
     * URL de fotos : https://ofi5.mef.gob.pe/inviertews/Repseguim/ResumF12B?codigo=2490719
     */

    public function up(): void
    {
        Schema::create('fotos_obra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obra_id')->constrained('obras');
            $table->json('files_path')->nullable();
            $table->string('endpoint_name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fotos_obra', function(Blueprint $table){
            $table->dropConstrainedForeignId('obra_id');
        });
        Schema::dropIfExists('fotos_obra');
    }
};
