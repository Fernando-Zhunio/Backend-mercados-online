<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
				$table->id();
				$table->bigInteger("id_usuario")->unsigned();
				$table->bigInteger("id_establecimiento")->unsigned()->nullable();
				$table->bigInteger("id_transportista")->unsigned()->nullable();
				$table->double("costo_venta");
				$table->double("costo_envio")->default(0);
				$table->double("total");
				$table->enum("forma_pago", ["EFECTIVO", "TARJETA"])->default("EFECTIVO");
				$table->timestamp("fecha_registro")->useCurrent();
				$table->timestamp("fecha_actualiza")->nullable();
				$table->enum("tipo", ["MERCADO", "NEGOCIO"])->default("MERCADO");

				$table->foreign("id_usuario")->references("id")->on("usuarios");
				$table->index("fecha_registro");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
