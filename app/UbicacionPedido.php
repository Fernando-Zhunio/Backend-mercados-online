<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UbicacionPedido extends Model
{
	public $table = "ubicacion_pedidos";
	public $timestamps = false;
	protected $fillable = ['id_pedido', 'direccion_entrega', 'celular_contacto', 'lat_entrega', 'lng_entrega', 'lat_transportista', 'lng_transportista'];
}
