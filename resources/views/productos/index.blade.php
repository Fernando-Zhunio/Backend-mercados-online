@extends('layout.index')
@section('title', 'Productos')

@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<span>Productos</span>
	</h2>
	<div class="pedidos-container">
		<table class="custom-table">
			<thead>
				<tr>
					<th>Nro.</th>
					<th>Foto</th>
					<th>Nombre</th>
					<th>Precio</th>
					<th>Unidad</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($productos as $producto)
				<tr>
					<td>{{$producto->id}}</td>
					<td><img src="{{asset('storage/' . $producto->url_imagen)}}" alt="Producto" style="width: 75px"></td>
					<td>{{$producto->nombre}}</td>
					<td>${{$producto->precio}}</td>
					<td>{{$producto->unidades}}</td>
					<td>
						<a href="{{ action('ProductoController@showProducto', ['id' => $producto->id]) }}" class="btn-detalles">Ver detalles</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $productos->links() }}
	</div>
</main>
@endsection