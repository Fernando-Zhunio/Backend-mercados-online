<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NegociosSubcategorias extends Model
{
    public $table = "negocios_subcategorias";
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'orden',
        'id_negocio'
    ];
}
