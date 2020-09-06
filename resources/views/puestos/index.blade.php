@extends('layout.index')
@section('title', 'Puestos')

@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<span>Puestos</span>
	</h2>
	<div class="pedidos-container">
		<table class="custom-table">
			<thead>
				<tr>
					<th>Nro.</th>
					<th>Codigo</th>
					<th>Vendedor</th>
					<th>Mercado</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($puestos as $puesto)
				<tr>
					<td>{{$puesto->id}}</td>
					<td>{{$puesto->codigo}}</td>
					<td>{{$puesto->vendedor->nombres}} {{$puesto->vendedor->apellidos}}</td>
					<td>{{$puesto->mercado}}</td>
					<td>
						<a href="{{ action('PuestoController@showPuesto', ['id' => $puesto->id]) }}" class="btn-detalles">Ver detalles</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $puestos->links() }}
	</div>
</main>
@endsection