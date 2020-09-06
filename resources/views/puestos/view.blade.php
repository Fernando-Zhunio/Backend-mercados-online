@extends('layout.index')
@section('title', 'Puestos')

@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<span>Detalle de puesto</span>
	</h2>
	<div class="pedidos-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-9">
					<div class="row">
						<div class="col-3 modal-info">
							<label for="">Mercado</label>
							<label>{{ $puesto->mercado }}</label>
						</div>
						<div class="col-3 modal-info">
							<label for="">Vendedor</label>
							<label>
								<a href="{{action('UsuarioController@showUsuario', ['id' => $puesto->vendedor->id])}}">
									{{ $puesto->vendedor->nombres }} {{ $puesto->vendedor->apellidos }}
								</a>
							</label>
						</div>
					</div>
				</div>
				<div class="col-3">
					<img style="width: 100%" src="{{asset('storage/' . $puesto->vendedor->imagen_perfil)}}" alt="Imagen vendedor">
				</div>
			</div>

			<div class="row">
				<table class="custom-table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Foto</th>
							<th>Nombre</th>
							<th>Precio</th>
							<th>Unidad</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($productos as $producto)
						<tr>
							<td>{{$producto->id}}</td>
							<td><img src="{{asset('storage/' . $producto->url_imagen)}}" alt="Producto" style="width: 75px"></td>
							<td><a href="{{action('ProductoController@showProducto', ['id' => $producto->id])}}">{{$producto->nombre}}</a></td>
							<td>{{$producto->precio}}</td>
							<td>{{$producto->unidades}}</td>
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