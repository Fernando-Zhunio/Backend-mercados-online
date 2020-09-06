<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
   public $table = "pedidos";
	public $timestamps = false;
	protected $fillable = ['id_usuario', 'id_establecimiento', 'id_transportista', 'costo_venta', 'costo_envio', 'total','tipo'];

	protected $appends = ['estado'];

	public function datosEntrega(){
		return $this->hasOne(UbicacionPedido::class, 'id_pedido');
	}

	public function getEstadoAttribute(){
		return $this->datosEntrega()->first('estado')->estado;
	}

	public function mercado(){
		return $this->belongsTo(Mercado::class, 'id_establecimiento', 'id');
	}

	public function getNombreMercadoAttribute(){
		return $this->mercado()->first('nombre')->nombre;
	}
	
	public function cliente(){
		return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
	}

	public function entrega(){
		return $this->hasOne(UbicacionPedido::class, 'id_pedido', 'id');
	}

	public function detalles(){
		return $this->hasMany(DetallePedido::class, 'id_venta');
	}

	public function transportista(){
		return $this->hasOne(Usuario::class, 'id', 'id_transportista');
	}

	public function negocio(){
		return $this->belongsTo(Tienda::class, 'id_establecimiento', 'id');
	}

	public function getNombreNegocioAttribute(){
		return $this->negocio()->first('nombre')->nombre;
	}
}
