<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMercadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercados', function (Blueprint $table) {
				$table->id();
				$table->string("codigo_mercado");
				$table->string("nombre");
				$table->string("descripcion", 500);
				$table->string("direccion", 500)->nullable();
				$table->string("ciudad")->default("GUAYAQUIL");
				$table->string("latitud")->nullable();
				$table->string("longitud")->nullable();
				$table->timestamp("fecha_registro")->useCurrent();
				$table->tinyInteger("estado")->default(1);
				$table->string("url_imagen")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercados');
    }
}
