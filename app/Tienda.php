<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
	public $table = "negocios";
	public $timestamps = false;
	protected $fillable = [
		'id_usuario',
		'nombre',
		'direccion', 
		'ciudad', 
		'latitud', 
		'longitud', 
		'creditos_abonados', 
		'creditos_utilizados', 
		'fecha_actualiza', 
		'tipo_negocio', 
		'descripcion',
		'telefono'
	];

	public function usuario(){
		return $this->hasOne(Usuario::class, 'id', 'id_usuario');
	}

	public function categoria(){
		return $this->hasONe(CategoriaNegocio::class, 'id', 'tipo_negocio');
	}
}
