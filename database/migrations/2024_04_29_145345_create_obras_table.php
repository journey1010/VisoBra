<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     /**
      * Para cada columna tendra una asignacion al lado izquiero que indicara de donde se esta obteniendo el dato.
      * Los tipos son CA(consulta avanzada), SSI(sistema de seguimeinto de inversiones) y GI(geoinvierte).
      * ----------------------------------------------------------------------------------------------------------
      * CA link de enlace : https://ofi5.mef.gob.pe/inviertePub/ConsultaPublica/ConsultaAvanzada
      * SSI link de enlace : https://ofi5.mef.gob.pe/ssi/ssi/Index
      * GI link de enlace : https://ofi5.mef.gob.pe/geoinvierteportal/
      */
    public function up(): void
    {
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funcion_id')->constrained('funcion');
            $table->foreignId('programa_id')->constrained('programa');
            $table->foreignId('subprograma_id')->constrained('subprograma');
            $table->foreignId('sector')->constrained('sector');
            $table->string('codigo_unico_inversion')->unique();
            $table->string('codigo_snip')->nullable()->unique();
            $table->string('nombre_inversion', 1000);
            $table->float('monto_viable', 3);
            $table->string('situacion');
            $table->string('estado_inversion');
            $table->enum('nivel_gobierno', ['GL', 'GR', 'GN']);
            $table->string('entidad');
            $table->string('unidad_opmi')->nullable();
            $table->string('responsable_opmi')->nullable();
            $table->string('unidad_uei')->nullable();
            $table->string('responsable_uei')->nullable();
            $table->string('responsable_uf')->nullable();
            $table->string('entidad_opi')->nullable();
            $table->string('responsable_opi')->nullable();
            $table->string('ejecutora')->nullable();
            $table->date('fecha_registro')->nullable();
            $table->string('ultimo_estudio')->nullable();
            $table->string('estado_estudio')->nullable();
            $table->string('nivel_viabilidad')->nullable();
            $table->string('responsable_viabilidad')->nullable();
            $table->date('fecha_viabilidad')->nullable();
            $ta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obras', function(Blueprint $table){
            $table->dropConstrainedForeignId('funcion_id');
            $table->dropConstrainedForeignId('programa_id');
            $table->dropConstrainedForeignId('subprograma_id');
            $table->dropConstrainedForeignId('sector');
        });
        Schema::dropIfExists('obras');
    }
};
