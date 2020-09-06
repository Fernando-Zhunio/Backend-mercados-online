@extends('layout.index')
@section('title', 'Editar usuarios')

@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<span>Editar transportista</span>
	</h2>
	<div class="pedidos-container">
		<form class="row ml-1 mt-3 action=" action="{{action('UsuarioController@updateUsuario')}}" method="POST">
			@csrf

			<input type="hidden" name="id" value="{{ $usuario->id }}">
			<div class="form-group col-3">
				<input type="text" class="form-input" name="nombres" placeholder="Nombres" value="{{$usuario->nombres}}" required>
			</div>
			<div class="form-group col-3">
				<input type="text" class="form-input" name="apellidos" placeholder="Apellidos" value="{{ $usuario->apellidos }}" required>
			</div>
			<div class="form-group col-3">
				<input type="text" class="form-input" name="celular" placeholder="Celular" value="{{ $usuario->celular }}" required>
			</div>
			<div class="form-group col-3">
				<input type="email" class="form-input" name="email" placeholder="Email" value="{{ $usuario->email }}" required>
			</div>
			<div class="col-12">
				<hr>
			</div>

			<div class="col-12 d-flex align-self-end">
				<button type="submit" class="">Editar</button>
			</div>
			@if($errors->any())
			<span class="text-danger mt-3" style="display: block">{{ $errors->first() }}</span>
			@endif
		</form>
	</div>

	</div>
</main>
@endsection