<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoPuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_puestos', function (Blueprint $table) {
				$table->id();
				$table->bigInteger("id_producto")->unsigned();
				$table->bigInteger("id_puesto")->unsigned();

				$table->foreign("id_puesto")->references("id")->on("puestos");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto_puestos');
    }
}
