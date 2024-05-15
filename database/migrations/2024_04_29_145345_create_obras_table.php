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
      * Tipo de metodo: GET (para todos los link, excepto para CA. El metodo de CA es POST)
      */
    public function up(): void
    {
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funcion_id')->constrained('funcion');
            $table->foreignId('programa_id')->constrained('programa');
            $table->foreignId('subprograma_id')->constrained('subprograma');
            $table->foreignId('sector_id')->constrained('sector');
            $table->string('codigo_unico_inversion')->nullable()->unique(); //CA
            $table->string('codigo_snip')->nullable()->unique(); //CA
            $table->string('nombre_inversion', 1000)->nullable()->fulltext(); //CA
            $table->float('monto_viable', 3); //CA
            $table->string('situacion'); //CA
            $table->string('estado_inversion'); //CA
            $table->enum('nivel_gobierno', ['GL', 'GR', 'GN']);  //CA
            $table->string('entidad'); //CA
            $table->string('unidad_opmi')->nullable(); //CA
            $table->string('responsable_opmi')->nullable(); //CA
            $table->string('unidad_uei')->nullable(); //CA
            $table->string('responsable_uei')->nullable(); //CA
            $table->string('unidad_uf')->nullable(); //CA
            $table->string('responsable_uf')->nullable(); //CA
            $table->string('entidad_opi')->nullable(); //CA
            $table->string('responsable_opi')->nullable(); //CA
            $table->string('ejecutora')->nullable(); //CA 
            $table->date('fecha_registro')->nullable(); //CA
            $table->string('ultimo_estudio')->nullable(); //CA
            $table->string('estado_estudio')->nullable(); //CA
            $table->string('nivel_viabilidad')->nullable(); //CA
            $table->string('responsable_viabilidad')->nullable(); //CA
            $table->date('fecha_viabilidad')->nullable(); //CA
            $table->float('costo_actualizado')->nullable(); //CA
            $table->text('descripcion_alternativa')->nullable();
            $table->integer('beneficiaros_habitantes', false, true)->nullable(); //CA
            $table->float('devengado_año_vigente', 3)->nullable(); //CA
            $table->float('devengado_año_anterior')->nullable(); //CA
            $table->integer('pim_año_vigente')->nullable(); //CA
            $table->float('devengado_acumulado',3)->nullable(); //CA
            $table->enum('marco', ['SNIP', 'INVIERTE']); //CA
            $table->float('saldo_por_financiar', 3)->nullable(); //CA
            $table->char('año_mes_primer_devengado', 7)->nullable(); //CA
            $table->char('año_mes_ultimo_devengado', 7)->nullable(); //CA
            $table->boolean('incluido_programacion_pmi')->nullable(); //CA
            $table->boolean('incluido_ejecucion_pmi')->nullable(); //CA
            $table->string('ganador_fronipel')->nullable(); //CA
            $table->string('registro_cierre')->nullable(); // CA
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obras', function(Blueprint $table){
            $table->dropIndex('obras_nombre_inversion_fulltext');
            $table->dropConstrainedForeignId('funcion_id');
            $table->dropConstrainedForeignId('programa_id');
            $table->dropConstrainedForeignId('subprograma_id');
            $table->dropConstrainedForeignId('sector_id');
        });
        Schema::dropIfExists('obras');
    }
};
