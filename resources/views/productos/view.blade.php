@extends('layout.index')
@section('title', 'Producto')

@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<span>Detalle producto</span>
	</h2>
	<div class="pedidos-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-9">
					<div class="row">
						<div class="col-3 modal-info">
							<label for="">Nombre producto</label>
							<label>{{ $producto->nombre }}</label>
						</div>
						<div class="col-3 modal-info">
							<label for="">Unidades</label>
							<label>{{$producto->unidades}}</label>
						</div>
						<div class="col-6 modal-info">
							<label for="">Descripción</label>
							<label>{{ $producto->descripcion }}</label>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-3 modal-info">
							<label for="">Precio</label>
							<label>${{ $producto->precio }}</label>
						</div>
						@if($producto->fuente == "PUESTO")
						<div class="col-3 modal-info">
							<label for="">Puesto</label>
							<label>
								<a href="{{action('PuestoController@showPuesto', ['id' => $producto->puesto->id])}}">
								{{ $producto->puesto->codigo }}
								</a>
							</label>
						</div>
						@else
						<div class="col-3 modal-info">
							<label for="">Tienda</label>
							<label>
								<a href="{{ action('TiendaController@showNegocio', ['id' => $producto->negocio->tienda->id ]) }}">
									{{ $producto->negocio->tienda->nombre }}
								</a>
							</label>
						</div>
						@endif
					</div>

				</div>
				<div class="col-3">
					<img src="{{asset('storage/' . $producto->url_imagen)}}" alt="Imagen producto" style="width:100%">
				</div>
			</div>
		</div>
	</div>
</main>
@endsection