<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
				$table->id();
				$table->string("usuario")->unique();
				$table->string("password");
				$table->string("auth_token")->nullable();
				$table->string("email")->unique();
				$table->string("nombres");
				$table->string("apellidos");
				$table->text("direccion")->nullable();
				$table->string("celular")->nullable();
				$table->string("gc_token")->nullable();
				$table->timestamp("fecha_registro")->useCurrent();
				$table->tinyInteger("estado")->default(1);
				$table->enum("rol", ["ADMIN", "CLIENTE", "VENDEDOR", "TRANSPORTISTA", "TIENDERO"])->default("CLIENTE");
				$table->string("imagen_perfil")->nullable();

				$table->index("usuario");
				$table->index("password");
				$table->index("auth_token");
				$table->index("gc_token");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
