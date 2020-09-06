<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
	public $table = "usuarios";
	public $timestamps = false;
	protected $fillable = ['usuario', 'password', 'email', 'nombres', 'apellidos', 'direccion', 'celular', 'gc_token', 'auth_token', 'rol'];

	public function puesto(){
		return $this->hasOne(Puesto::class, 'id_vendedor', 'id');
	}

	public function negocios(){
		return $this->hasMany(Tienda::class, 'id_usuario', 'id');
	}
}
