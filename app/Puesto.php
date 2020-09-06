<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
	public $table = "puestos";
	public $timestamps = false;
	protected $fillable = ['codigo', 'id_mercado', 'id_vendedor'];

	public function relacion(){
		return $this->belongsTo(ProductoPuesto::class, 'id', 'id_puesto');
	}
// ----------------------fz
	public function productos(){
		return $this->belongsToMany(Producto::class, 'producto_puestos','id_puesto','id_producto');
	}

	
// -----------------end fz
	public function getProductosAttribute(){
		return Producto::whereIn('id', $this->relacion()->get('id_producto')->map(function($data){
			return $data->id_producto;
		}))->get();
	}

	function convertProductos($producto){
		return $producto->id_producto;
	}

	public function vendedor(){
		return $this->hasOne(Usuario::class, 'id', 'id_vendedor');
	}

	public function mercado(){
		return $this->belongsTo(Mercado::class, 'id_mercado', 'id');
	}

	public function getMercadoAttribute(){
		return $this->mercado()->first('nombre')->nombre;
	}
}
