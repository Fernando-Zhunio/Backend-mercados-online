<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedido', function (Blueprint $table) {
				$table->id();
				$table->bigInteger("id_venta")->unsigned();
				$table->bigInteger("id_vendedor")->unsigned();
				$table->bigInteger("id_puesto")->unsigned()->nullable();
				$table->bigInteger("id_producto")->unsigned();
				$table->double("precio");
				$table->integer("cantidad");
				$table->double("subtotal");

				$table->foreign("id_venta")->references("id")->on("pedidos");
				$table->foreign("id_vendedor")->references("id")->on("usuarios");
				$table->foreign("id_puesto")->references("id")->on("puestos");
				$table->foreign("id_producto")->references("id")->on("productos");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pedido');
    }
}
