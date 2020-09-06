<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mercado extends Model
{
	public $table = "mercados";
	public $timestamps = false;
	protected $fillable = ['nombre', 'codigo_mercado', 'descripcion', 'url_imagen','direccion', 'longitud','latitud','tipo_local','ciudad'];

	public function puestos(){
		return $this->hasMany(Puesto::class, 'id_mercado');
	}

	public function getCantidadPuestosAttribute(){
		return $this->puestos()->select('id')->count();
	}
}
