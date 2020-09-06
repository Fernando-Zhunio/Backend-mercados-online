<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNegociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('negocios', function (Blueprint $table) {
				$table->id();
				$table->integer("id_usuario")->unsigned();
				$table->string("nombre");
				$table->string("descripcion", 1000);
				$table->string("direccion", 500)->nullable();
				$table->string("ciudad")->default("GUAYAQUIL");
				$table->string("latitud")->nullable();
				$table->string("longitud")->nullable();
				$table->string("telefono")->nullable();
				$table->decimal("creditos_totales")->nullable()->default(0);
				$table->bigInteger("tipo_negocio")->unsigned();
				$table->string("url_imagen")->nullable();
				$table->tinyInteger("estado")->default(2);
				$table->timestamp("fecha_registro")->useCurrent();
				$table->timestamp("fecha_actualiza")->nullable();
				
				$table->foreign("tipo_negocio")->references("id")->on("categoria_negocios");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tiendas');
    }
}
