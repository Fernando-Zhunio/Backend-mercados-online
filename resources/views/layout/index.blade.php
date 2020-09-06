<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>@yield('title') - {{ config('app.name') }}</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
	<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="https://kit.fontawesome.com/2b8423e0d8.js" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container-fluid no-gutters h-100">
		<div class="row">
			<div class="col-12 no-gutters" style="padding: 0;height: 52px">
				@include('layout.partial.header')
			</div>
		</div>

		<div class="row">
			<div class="col-2 no-gutters" style="padding: 0">
				@include('layout.partial.sidebar')
			</div>
			<div class="col-10">
				@yield('content')
			</div>
		</div>
	</div>

</body>
</html>