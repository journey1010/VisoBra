<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Esta tabla contiene informacion sobre la lista total de obras. Esto sirve para poblar la base de datos
     * Con todas las obras en la region loreto. Además, es para agregar nuevos registros
     * LINK : https://ofi5.mef.gob.pe/inviertePub/ConsultaPublica/traeListaProyectoConsultaAvanzada
     * METODO: POST
     * Parametros: {"filters":"","ip":"","cboNom":"1","txtNom":"","cboDpto":"16","cboProv":"0","cboDist":"0","optUf":"*","cboGNSect":"*","cboGNPlie":"","cboGNUF":"","cboGR":"*","cboGRUf":"","optGL":"*","cboGLDpto":"*","cboGLProv":"*","cboGLDist":"*","cboGLUf":"","cboGLManPlie":"*","cboGLManUf":"","cboSitu":"*","cboNivReqViab":"*","cboEstu":"*","cboEsta":"*","optFecha":"*","txtIni":"","txtFin":"","chkMonto":false,"txtMin":"","txtMax":"","tipo":"1","cboFunc":"0","chkInactivo":"0","cboDivision":"0","cboGrupo":"0","rbtnCadena":"T","isSearch":false,"PageSize":10,"PageIndex":1,"sortField":"MontoAlternativa","sortOrder":"desc","chkFoniprel":""}
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
