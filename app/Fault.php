<?php

namespace App;

class Fault
{
	public $codigo = "";
	public $mensaje = "";

	public function __construct($codigo, $mensaje, $valores = null){
		$this->codigo = $codigo;
		$this->mensaje = $mensaje;

		if($valores != null){
			$this->mensaje = str_replace("[]", "[$valores]", $this->mensaje);
		}
	}
}
