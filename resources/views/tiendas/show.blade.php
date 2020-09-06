@extends('layout.index')
@section('title', 'Negocio')

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
		<span>Detalle de negocio</span>
		<div class="module-options">
			<a href="{{action('TiendaController@showCreditosPage', ['id' => $negocio->id])}}"
				class="btn-title edit">Agregar creditos</a>
		</div>
	</h2>
	<div class="pedidos-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-9">
					<div class="row">
						<div class="col-3 modal-info">
							<label for="">Nombre</label>
							<label>{{ $negocio->nombre }}</label>
						</div>
						<div class="col-3 modal-info">
							<label for="">Dueño</label>
							<label>
								<a href="{{action('UsuarioController@showUsuario', ['id' => $negocio->usuario->id])}}">
									{{ $negocio->usuario->nombres }} {{ $negocio->usuario->apellidos }}
								</a>
							</label>
						</div>
						<div class="col-6 modal-info">
							<label for="">Contacto</label>
							<label>{{ $negocio->telefono }}</label>
						</div>
					</div>

					<div class="row mt-4">
						<div class="col-6 modal-info">
							<label for="">Dirección</label>
							<label>{{ $negocio->direccion }}</label>
						</div>
						<div class="col-6 modal-info">
							<label for="">Descripción</label>
							<label>{{ $negocio->descripcion }}</label>
						</div>
					</div>

					<div class="row mt-4">
						<div class="col-4 modal-info">
							<label for="">Creditos Actuales</label>
							<label>{{ $negocio->creditos_totales }}</label>
						</div>
						<div class="col-3 modal-info">
							<label for="">Estado</label>
							<label>{{ pipeEstado($negocio->estado) }}</label>
						</div>
						<div class="col-4 modal-info">
							<label for="">Categoria</label>
							<label>{{ $negocio->categoria->nombre }}</label>
						</div>
					</div>
				</div>
				<div class="col-3">
					<img src="{{asset('storage/images/neg-' . $negocio->id . '.jpg')}}" alt="Imagen negocio"
						style="width: 100%">
				</div>
			</div>

			<div class="row">
				<table class="custom-table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nombre</th>
							<th>Precio</th>
						</tr>
					</thead>
					<tbody>
						@foreach($productos as $producto)
						<tr>
							<td>{{ $producto->id }}</td>	
							<td><a href="{{action('ProductoController@showProducto', ['id' => $producto->id])}}">{{$producto->nombre}}</a></td>
							<td>${{$producto->precio}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				{{ $productos->links() }}
			</div>
		</div>
	</div>
</main>
@endsection