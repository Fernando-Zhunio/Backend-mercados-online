@extends('layout.index')
@section('title', 'Usuarios')

@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<div>
			<span>Usuarios</span>
			{{-- <div class="btn-filter" style="display: inline-block">
				<i class=" fas fa-filter" style="font-size: .6rem"></i>
				<span style="font-weight: 400;">Filtrar</span>
			</div> --}}
		</div>
		<a href="{{action('UsuarioController@createUser')}}" class="btn-title"><i class="fas fa-plus"
				style="padding-right: 8px"></i>Agregar</a>

	</h2>
	<div class="filter-container">
		<span>Mostrar</span>
		<select name="" id="filter-usuarios">
			<option value="0" {{ $type == 'all' ? 'selected' : '' }}>Todos</option>
			<option value="1" {{ $type == 'cliente' ? 'selected' : '' }}>Clientes</option>
			<option value="2" {{ $type == 'vendedor' ? 'selected' : '' }}>Vendedores</option>
			<option value="3" {{ $type == 'transportista' ? 'selected' : '' }}>Transportistas</option>
			<option value="4" {{ $type == 'tiendero' ? 'selected' : '' }}>Tiendero</option>
		</select>

	</div>
	<div class="pedidos-container">
		<table class="custom-table">
			<thead>
				<tr>
					<th>Nro.</th>
					<th>Nombres</th>
					<th>Apellidos</th>
					<th>Tipo</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($usuarios as $usuario)
				<tr>
					<td>{{$usuario->id}}</td>
					<td>{{$usuario->nombres}}</td>
					<td>{{$usuario->apellidos}}</td>
					<td>{{$usuario->rol}}</td>
					<td>
						<a href="{{ action('UsuarioController@showUsuario', ['id' => $usuario->id]) }}"
							class="btn-detalles">Ver
							detalles</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $usuarios->appends($_GET)->links() }}
	</div>
	@if (session('mensaje'))
	<div class="alert alert-success mt-4">
		{{ session('mensaje') }}
	</div>
	@endif
</main>

<script>
	$(document).ready(function(e){
		$("#filter-usuarios").change(function(e){
			const id = $(this).val();

			switch(id){
				case '0': window.location.assign('usuarios');break;
				case '1': window.location.assign('usuarios?type=cliente');break;
				case '2': window.location.assign('usuarios?type=vendedor');break;
				case '3': window.location.assign('usuarios?type=transportista');break;
				case '4': window.location.assign('usuarios?type=tiendero');break;
			}
		});
	});
</script>
@endsection