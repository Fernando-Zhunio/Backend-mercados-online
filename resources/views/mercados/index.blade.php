@extends('layout.index')
@section('title', 'Mercados')

@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<span>Mercados</span>
		<a href="{{action('MercadoController@createMercado')}}" class="btn-title"><i class="fas fa-plus"
				style="padding-right: 8px"></i>Agregar</a>
	</h2>
	<div class="pedidos-container">
		<table class="custom-table">
			<thead>
				<tr>
					<th>Nro.</th>
					<th>Mercado</th>
					<th>Dirección</th>
					<th>Puestos</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($mercados as $mercado)
				<tr>
					<td>{{$mercado->id}}</td>
					<td>{{$mercado->nombre}}</td>
					<td>{{$mercado->direccion}}</td>
					<td>{{$mercado->cantidad_puestos}}</td>
					<td>
						<a href="{{ action('MercadoController@showMercado', ['id' => $mercado->id]) }}" class="btn-detalles">Ver
							detalles</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $mercados->links() }}
	</div>
	@if (session('mensaje'))
	<div class="alert alert-success mt-4">
		{{ session('mensaje') }}
	</div>
	@endif
</main>
@endsection