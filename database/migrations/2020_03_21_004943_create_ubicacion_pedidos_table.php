<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicacionPedidosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ubicacion_pedidos', function (Blueprint $table) {
			$table->id();
			$table->bigInteger("id_pedido")->unsigned();
			$table->string("direccion_entrega", 300);
			$table->string("celular_contacto");
			$table->string("lat_entrega")->nullable();
			$table->string("lng_entrega")->nullable();
			$table->string("lat_transportista")->nullable();
			$table->string("lng_transportista")->nullable();
			$table->enum("estado", ["WAITING", "IN_PROGRESS", "OUT_PROGRESS", "ENTREGADA", "CANCEL"])->default("WAITING");

			$table->foreign("id_pedido")->references("id")->on("pedidos");
			$table->index("estado");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ubicacion_pedidos');
	}
}
