@extends('layout.index')
@section('title', 'Mercado')

@section('content')
<main class="main-container">
	<h2 class="title d-flex mb-3 justify-content-between align-items-baseline">
		<span>Detalle de mercado</span>
		<div class="module-options">
			<a href="{{action('MercadoController@editMercado', ['id' => $mercado->id])}}" class="btn-title edit">Editar</a>
			<a href="{{action('MercadoController@deleteMercado', ['id' => $mercado->id])}}" class="btn-title danger" id="btn-delete">Eliminar</a>
		</div>
	</h2>
	<div class="pedidos-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-9">
					<div class="row">
						<div class="col-3 modal-info">
							<label for="">Mercado</label>
							<label>{{ $mercado->nombre }}</label>
						</div>
						<div class="col-3 modal-info">
							<label for="">Dirección</label>
							<label>{{ $mercado->direccion }}</label>
						</div>
						<div class="col-6 modal-info">
							<label for="">Descripción</label>
							<label>{{ $mercado->descripcion }}</label>
						</div>
					</div>
				</div>
				<div class="col-3">
					<img src="{{asset('storage/' . $mercado->url_imagen)}}"
						alt="Imagen mercado" style="width: 100%">
				</div>
			</div>

			<div class="row">
				<table class="custom-table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Codigo</th>
							<th>Vendedor</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($puestos as $puesto)
						<tr>
							<td>{{$puesto->id}}</td>
							<td><a href="{{action('PuestoController@showPuesto', ['id' => $puesto->id])}}">{{$puesto->codigo}}</a></td>
							<td>{{$puesto->vendedor->nombres}} {{$puesto->vendedor->apellidos}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				{{ $puestos->links() }}
			</div>
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