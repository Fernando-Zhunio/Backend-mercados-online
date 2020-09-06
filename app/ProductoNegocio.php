<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoNegocio extends Model
{
   public $table = "producto_negocios";
	public $timestamps = false;
	protected $fillable = ['id_producto', 'id_negocio','id_promocion'];

	public function tienda(){
		return $this->hasOne(Tienda::class, 'id', 'id_negocio');
	}
}
