@extends('layout.index')
@section('title', 'Usuario')

@php
function pipeEstado($estado){
switch ($estado) {
case 1: return "Activo"; break;
case 2: return "Inactiva"; break;
case 0: return "Eliminada"; break;
}
}
@endphp

@section('content')
<main class="main-container">
	<h2 class="title d-flex mb-3 justify-content-between align-items-baseline">
		<span>Detalle usuario</span>
		<div class="module-options">
			@if($usuario->rol == "TRANSPORTISTA")
			<a href="{{action('UsuarioController@editUsuario', ['id' => $usuario->id])}}" class="btn-title edit">Editar</a>
			@endif
			<a href="{{action('UsuarioController@deleteUsuario', ['id' => $usuario->id])}}" class="btn-title danger"
				id="btn-delete">Eliminar</a>
		</div>
	</h2>
	<div class="pedidos-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-9">
					<div class="row">
						<div class="col-3 modal-info">
							<label for="">Nombres</label>
							<label>{{ $usuario->nombres }}</label>
						</div>
						<div class="col-3 modal-info">
							<label for="">Apellidos</label>
							<label>{{ $usuario->apellidos }}</label>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-3 modal-info">
							<label for="">Tipo</label>
							<label>{{ $usuario->rol }}</label>
						</div>
						<div class="col-3 modal-info">
							<label for="">Celular</label>
							<label>{{ $usuario->celular }}</label>
						</div>
						<div class="col-3 modal-info">
							<label for="">Correo</label>
							<label>{{ $usuario->email }}</label>
						</div>
					</div>

					@if($usuario->rol == "VENDEDOR")
					<div class="row mt-4">
						<div class="col-3 modal-info">
							<label for="">Mercado</label>
							<label>{{ $usuario->puesto->mercado }}</label>
						</div>
						<div class="col-3 modal-info">
							<label for="">Puesto</label>
							<label>
								<a href="{{action('PuestoController@showPuesto', ['id' => $usuario->puesto->id])}}">{{ $usuario->puesto->codigo }}</a>
							</label>
						</div>
					</div>
					@endif

				</div>
				<div class="col-3">
					<img src="{{asset('storage/' . $usuario->imagen_perfil)}}" alt="Imagen usuario"
						style="width:100%">
				</div>
			</div>

			@if($usuario->rol == "TIENDERO")
			<div class="row">
				<table class="custom-table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nombre</th>
							<th>Direccion</th>
							<th>Creditos Actuales</th>
							<th>Estado</th>
						</tr>
					</thead>
					<tbody>
						@foreach($negocios as $negocio)
						<tr>
							<td>{{ $negocio->id }}</td>	
							<td>
								<a href="{{action('TiendaController@showNegocio', ['id' => $negocio->id])}}">
									{{$negocio->nombre}}
								</a>
							</td>
							<td>{{$negocio->direccion}}</td>
							<td>{{$negocio->creditos_totales}}</td>
							<td>{{pipeEstado($negocio->estado)}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				{{ $negocios->links() }}
			</div>

			@endif
		</div>
	</div>
</main>
<script>
	$(document).ready(function(e){
			$("#btn-delete").click(function(e){
				return confirm("Estas seguro");
			});
		});
</script>
@endsection