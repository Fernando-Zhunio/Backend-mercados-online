<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
				$table->id();
				$table->string("nombre");
				$table->string("descripcion", 500)->nullable();
				$table->bigInteger("id_categoria")->unsigned()->nullable();
				$table->double("precio");
				$table->string("unidades")->nullable();
				$table->string("url_imagen", 500)->nullable();
				$table->tinyInteger("estado")->default(1);
				$table->integer("stock")->default(0);
				$table->timestamp("fecha_registro")->useCurrent();
				$table->timestamp("fecha_actualiza")->nullable();
				$table->enum("fuente", ["PUESTO","NEGOCIO"]);

				$table->foreign("id_categoria")->references("id")->on("categoria_productos");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
