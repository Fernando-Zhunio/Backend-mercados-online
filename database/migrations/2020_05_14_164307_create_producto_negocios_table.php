<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoNegociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_negocios', function (Blueprint $table) {
				$table->id();
				$table->bigInteger("id_producto")->unsigned();
                $table->bigInteger("id_negocio")->unsigned();
                $table->integer("id_promocion");

				$table->foreign("id_negocio")->references("id")->on("negocios");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto_negocios');
    }
}
