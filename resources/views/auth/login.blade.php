<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Login - {{ config('app.name') }}</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<body>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-auto contenedor-login">
				<h3 style="font-size: 1rem; font-weight: 400;margin-top: 20px">Mercados Online</h3>
				<h2 style="font-weight: 600">Iniciar Sesión</h2>

				<form action="{{ action('AuthController@authenticateAdmin') }}" method="POST" style="margin-top: 40px">
					@csrf
					<div class="form-group">
						<input type="text" class="form-input" name="usuario" placeholder="Usuario" required>
					</div>

					<div class="form-group">
						<input type="password" class="form-input" name="password" placeholder="Password" required>
					</div>

					@if($errors->any())
					<span class="text-danger" style="display: block">{{ $errors->first() }}</span>
					@endif

					<div class="form-group" style="margin-top: 30px">
						<button class="btn-custom btn-block btn-custom-primary" type="submit">Ingresar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>

</html>