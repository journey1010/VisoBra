<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fuente de datos: https://ws.mineco.gob.pe/server/rest/services/cartografia_pip_georef_edicion_lectura/MapServer/0/query?f=json&where=UPPER(COD_UNICO)%20LIKE%20%27%252192666%25%27&returnGeometry=true&spatialRel=esriSpatialRelIntersects&maxAllowableOffset=0.01866138385297604&outFields=*&outSR=102100&resultRecordCount=1
     * Para ese link hay 2 elementos a tener a cuenta el codigo COD_UNICO(el cui) y MapServer/x (x puede ser 0 o 1, desconosco si hay mas números)
     * 
     **/

    public function up(): void
    {
        Schema::create('geo_obra', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor('obras_id')->constrained('obras');
            $table->string('provincia');
            $table->string('distrito');
            $table->string('departamento');
            $table->geography('coordenadas', subtype: 'point', srid: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('geo_obra', function(Blueprint $table){   
            $table->dropForeignIdFor('obras_id');
        });
        Schema::dropIfExists('geo_obra');
    }
};



