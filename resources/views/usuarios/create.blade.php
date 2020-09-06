@extends('layout.index')
@section('title', 'Nuevo usuarios')

@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<span>Nuevo transportista</span>
	</h2>
	<div class="pedidos-container">
		<form class="row ml-1 mt-3 action=" action="{{action('UsuarioController@storeUsuario')}}" method="POST">
			@csrf

			<div class="form-group col-3">
				<input type="text" class="form-input" name="nombres" placeholder="Nombres" required>
			</div>
			<div class="form-group col-3">
				<input type="text" class="form-input" name="apellidos" placeholder="Apellidos" required>
			</div>
			<div class="form-group col-3">
				<input type="text" class="form-input" name="celular" placeholder="Celular" required>
			</div>
			<div class="form-group col-3">
				<input type="email" class="form-input" name="email" placeholder="Email" required>
			</div>
			<div class="col-12">
				<hr>
			</div>

			<div class="col-12 d-flex align-self-end">
				<button type="submit" class="">Registrar</button>
			</div>
			@if($errors->any())
			<span class="text-danger mt-3" style="display: block">{{ $errors->first() }}</span>
			@endif
		</form>
	</div>

	</div>
</main>
@endsection