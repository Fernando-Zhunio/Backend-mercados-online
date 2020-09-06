@extends('layout.index')
@section('title', 'Negocio')

@section('content')
<main class="main-container">
	<h2 class="title d-flex mb-3 justify-content-between align-items-baseline">
		<span>Creditos del negocio</span>
	</h2>
	<div class="pedidos-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-4 modal-info">
					<label for="">Nombre</label>
					<label>{{ $negocio->nombre }}</label>
				</div>
				<div class="col-4 modal-info">
					<label for="">Creditos Actuales</label>
					<label>{{ $negocio->creditos_totales }}</label>
				</div>
			</div>
			<hr>
			<form class="row" action="{{action('TiendaController@addCreditos')}}" method="POST">
				@csrf
				<input type="hidden" name="id" value="{{$negocio->id}}">	
				<div class="col-4 modal-info">
					<label>Creditos Abonados ($)</label>
					<input type="number" min="1" required name="creditos">
				</div>
				<div class="col-12 mt-4 d-flex align-self-end">
					<button type="submit" class="">Registrar</button>
				</div>
			</form>
		</div>
	</div>
</main>
@endsection