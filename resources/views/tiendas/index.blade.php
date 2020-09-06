@extends('layout.index')
@section('title', 'Negocios')


@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<span>Negocios</span>
	</h2>
	<div class="pedidos-container">
		<table class="custom-table">
			<thead>
				<tr>
					<th>Nro.</th>
					<th>Nombre</th>
					<th>Dueño</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($negocios as $negocio)
				<tr>
					<td>{{$negocio->id}}</td>
					<td>{{$negocio->nombre}}</td>
					<td>{{$negocio->usuario->nombres}} {{ $negocio->usuario->apellidos }}</td>
					<td>
						<a href="{{ action('TiendaController@showNegocio', ['id' => $negocio->id]) }}" class="btn-detalles">Ver
							detalles</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $negocios->links() }}
	</div>
	@if (session('mensaje'))
	<div class="alert alert-success mt-4">
		{{ session('mensaje') }}
	</div>
	@endif
</main>
@endsection
