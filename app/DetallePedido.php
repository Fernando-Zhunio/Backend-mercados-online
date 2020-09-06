<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
   public $table = "detalle_pedido";
	public $timestamps = false;
	protected $fillable = ['id_venta', 'id_vendedor', 'id_producto', 'precio', 'cantidad', 'subtotal', 'id_puesto'];
	protected $appends = ['nombre_producto', 'unidades'];

	public function vendedor(){
		return $this->hasOne(Usuario::class, 'id', 'id_vendedor');
	}

	public function puesto(){
		return $this->hasOne(Puesto::class, 'id', 'id_puesto');
	}

	public function getNombreProductoAttribute(){
		return ($this->hasOne(Producto::class, 'id', 'id_producto'))->first('nombre')->nombre;
	}

	public function getUnidadesAttribute(){
		return  ($this->hasOne(Producto::class, 'id', 'id_producto'))->first('unidades')->unidades;
	}
}
