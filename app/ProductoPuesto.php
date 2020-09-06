<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoPuesto extends Model
{
	public $table = "producto_puestos";
	public $timestamps = false;
	protected $fillable = ['id_producto', 'id_puesto'];

	public function producto(){
		return $this->hasMany(Producto::class, 'id', 'id_producto');
	}
}
