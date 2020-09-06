<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
   public $table = "productos";
	public $timestamps = false;
	protected $fillable = ['nombre','descripcion','precio','id_categoria','id_puesto','url_imagen', 'unidades', 'fuente', 'stock'];
	protected $appends = ['nombre_categoria'];

	public $primary ="id_producto";
	public function relacionPuesto(){
		return $this->hasOne(ProductoPuesto::class, 'id_producto', 'id');
	}

	public function puestos(){
		return $this->hasToMany(Puesto::class,'id_puesto','id');
	}

	public function getPuestoAttribute(){
		return Puesto::find($this->relacionPuesto()->first('id_puesto')->id_puesto);
	}

	public function getIdPuestoAttribute(){
		return $this->relacionPuesto()->first('id_puesto')->id_puesto;
	}

	public function getIdNegocioAttribute(){
		return $this->negocio()->first('id_negocio')->id_negocio;
	}

	public function categoria(){
		return $this->hasOne(CategoriaProductos::class, 'id', 'id_categoria');
	}

	public function negocio(){
		return $this->hasOne(ProductoNegocio::class, 'id_producto', 'id');
	}

	public function getNombreCategoriaAttribute(){
		// return $this->categoria()->first('nombre')->nombre;
	}

	public function getPromocionAttribute(){
		return NegociosSubcategorias::find($this->negocio()->first('id_promocion')->id_promocion)->nombre;
	}

}
