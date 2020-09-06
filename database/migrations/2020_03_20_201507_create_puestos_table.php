<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puestos', function (Blueprint $table) {
				$table->id();
				$table->string("codigo");
				$table->bigInteger("id_mercado")->unsigned();
				$table->bigInteger("id_vendedor")->unsigned()->nullable();
				$table->tinyInteger("estado")->default(1);
				$table->timestamp("fecha_registro")->useCurrent();
				$table->timestamp("fecha_actualiza")->nullable();
			
				$table->foreign('id_mercado')->references('id')->on('mercados');
				$table->foreign('id_vendedor')->references('id')->on('usuarios');
			});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('puestos');
    }
}
