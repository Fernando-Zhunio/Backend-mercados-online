@extends('layout.index')
@section('title', 'Nuevo mercado')

@section('content')
<main class="main-container">
	<h2 class="title d-flex justify-content-between align-items-baseline">
		<span>Nuevo mercado</span>
	</h2>
	<div class="pedidos-container">
		<form class="row ml-1 mt-2 align-items-baseline" enctype="multipart/form-data" action="{{action('MercadoController@storeMercado')}}" method="POST">
			@csrf
			<div class="form-group col-3 upload-img" style="padding: 0; margin-bottom: 0">
				<img src="{{asset('img/background-img.png')}}" alt="">
			</div>

			<div class="col-9">
				<div class="row">
					<div class="form-group col-12">
						<label for="" class="form-label">Nombre</label>
						<input type="text" class="form-input" name="nombre" required>
					</div>
					<div class="form-group col-12">
						<label for="" class="form-label">Direccion</label>
						<input type="text" class="form-input" name="direccion" required>
					</div>
					<div class="form-group col-12">
						<label for="" class="form-label">Descripcion</label>
						<input type="text" class="form-input" name="descripcion" required>
					</div>
				</div>
			</div>

			<input type="file" name="foto" style="display: none" class="imagen-mercado">

			<div class="col-12">
				<hr>
			</div>
			<div class="col-12 mt-4 d-flex align-self-end">
				<button type="submit" class="">Registrar</button>
			</div>
		</form>
	</div>
</main>

<script>
	$(document).ready(function(e){
		$(".upload-img").click(function (e) {
			$(".imagen-mercado").trigger('click');
		});

		$(".imagen-mercado").change(function (e) {
			const files = e.target.files;
			fileImg = files;

			if (FileReader && files && files.length) {
				var fr = new FileReader();
				fr.onload = function () {

					$(".upload-img img").attr('src', fr.result);
				}
				fr.readAsDataURL(files[0]);
			}
		});
	});
</script>

@endsection