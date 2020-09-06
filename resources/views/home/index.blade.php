@extends('layout.index')
@section('title', 'Home')

@section('content')
<main class="main-container">
	<h2 class="title">Pedidos</h2>

	<div class="filter-container">
		<span>Mostrar</span>
		<select name="" id="filter-pedidos">
			<option value="0" {{ $type == 'all' ? 'selected' : '' }}>Todos</option>
			<option value="1" {{ $type == 'pendientes' ? 'selected' : '' }}>Pendientes</option>
			<option value="2" {{ $type == 'progreso' ? 'selected' : '' }}>En Progreso</option>
			<option value="3" {{ $type == 'finalizado' ? 'selected' : '' }}>Finalizadas</option>
		</select>
	</div>

	<input type="hidden" value="{{session('token')}}" id="_token">

	<div class="pedidos-container">
		<table class="custom-table">
			<thead>
				<tr>
					<th>Nro.</th>
					<th>Establecimiento</th>
					<th>Tipo</th>
					<th>Cliente</th>
					<th>Dirección</th>
					<th>Estado</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pedidos as $pedido)
				<tr>
					<td>{{ $pedido->id }}</td>
					<td>{{ $pedido->tipo == "MERCADO" ? $pedido->mercado->nombre : $pedido->negocio->nombre }}</td>
					<td>{{ $pedido->tipo }}</td>
					<td>{{ $pedido->cliente->nombres }} {{ $pedido->cliente->apellidos }}</td>
					<td>{{ $pedido->entrega->direccion_entrega }}</td>

					@switch($pedido->estado)
					@case('WAITING')
					<td style="text-align: center;">Pendientes</td>
					@break
					@case('IN_PROGRESS')
					<td style="text-align: center;">En Progreso</td>
					@break
					@case('ENTREGADA')
					<td style="text-align: center;">Entregado</td>
					@break
					@endswitch
					<td>
						<button type="button" data-pedido="{{ $pedido->id}}" class="btn-detalles" data-toggle="modal"
							data-target="#exampleModal">Ver detalles</button>
					</td>
				</tr>
				@endforeach

			</tbody>
		</table>
		{{ $pedidos->appends($_GET)->links() }}
		@if (session('mensaje'))
		<div class="alert alert-success mt-4">
			{{ session('mensaje') }}
		</div>
		@endif
	</div>
</main>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 90%">
		<div class="modal-content loading">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detalle de pedido <span class="id-pedido"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal-body">
				<div class="loader hide-loader"></div>
				<div class="row hide" id="contenedor-informacion">
					<div class="col-8" id="contenedor-campos">

					</div>

					<div class="col-4">
						<div id="map" style="height: 500px"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form action="{{ route('asignar-transportista') }}" method="POST">
					@csrf
					<input type="hidden" value="" name="id_transportista" id="idTransportista">
					<input type="hidden" value="" name="id_pedido" id="idVenta">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary btn-actualizar-pedido">Actualizar</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('app.maps_api') }}&callback=initMap" async defer>
</script>

<script src="{{ asset('js/map.js') }}"></script>
<script>
	$(document).ready(function(e){
		$(".btn-detalles").click(function(e){
			$("#contenedor-campos").children().remove();
			$("#contenedor-informacion").addClass('hide');
			$(".btn-actualizar-pedido").show();
			const id = $(this).attr('data-pedido');

			$("#idVenta").val(id);

			const token = $("#_token").val();

			$.ajax({
				type: "GET",
				url: `api/pedidos/${id}?type=FULL`,
				dataType: "json",
				beforeSend: function(request){
					$(".loader").removeClass('hide-loader');
					request.setRequestHeader("Authorization", "Bearer " + token);
					request.setRequestHeader("rol", "ADMIN");
				},
				success: function (datos) {
					$(".loader").addClass('hide-loader');
					$("#contenedor-informacion").removeClass('hide');

					if(datos.estado != 'WAITING'){
						$(".btn-actualizar-pedido").hide();
					}

					if(datos.tipo == "NEGOCIO"){
						$(".btn-actualizar-pedido").hide();
					}

					position = {lat: parseFloat(datos.entrega.lat_entrega), lng: parseFloat(datos.entrega.lng_entrega)};
					map.setCenter(position);
 					var marker = new google.maps.Marker({ position: position, map: map });

					$(".id-pedido").text("#" + datos.id);

					let row = $(`
						<div class="row">
							${constructDatos("Cliente", datos.cliente.nombres + ' ' + datos.cliente.apellidos, null)}	
							${datos.tipo == "MERCADO" ? constructDatos("Establecimiento", datos.mercado.nombre, null) : constructDatos("Establecimiento", datos.negocio.nombre, null)}	
							${constructDatos("Estado", datos.estado, pipeEstado)}	
							${constructDatos("Entrega en", datos.entrega.direccion_entrega, null)}	
						</div>
						<div class="row contenedor-transportista mt-3">
							${constructDatos("Celular cliente", datos.cliente.celular, null)}	
							${datos.tipo == "MERCADO" ? renderMotorizado(datos.id_motorizado,datos.estado, datos.transportista) : ""}
						</div>
						<hr>
						<div class="row">
							<h5 style="padding-left: 18px">Lista de producto</h5>
							<div class="col-12 scroll-container" >
								<table class="custom-table">
									<thead>
										<tr>
											<th>Producto</th>
											<th>Cantidad</th>
											<th>Precio Uni.</th>
											<th>Precio Total</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<hr>
						<div class="col-auto contenedor-valores">
							<div><span class="val-label">Subtotal</span> <span class="val-value">$${datos.costo_venta}</span></div>
							<div><span class="val-label">Envio</span> <span class="val-value">$${datos.costo_envio}</span></div>
							<div><span class="val-label">Total</span> <span class="val-value">$${datos.total}</span></div>
						</div>
					`);

					if(datos.tipo != "NEGOCIO"){
						row.find("thead").children("tr").append(`
							<th>Puesto</th>
							<th>Vendedor</th>
						`);
					}

					datos.detalles.forEach(d => {
						const tr = $(`
							<tr>
								<td><a href="productos/${d.id_producto}">${d.nombre_producto}</a></td>
								<td>${d.cantidad}</td>
								<td>$${d.precio}</td>
								<td>$${d.subtotal}</td>
							</tr>
						`);

						if(datos.tipo != "NEGOCIO"){
							tr.append(`
								<td><a href="puestos/${d.puesto.id}">${d.puesto.codigo}</a></td>
								<td>${d.vendedor.nombres} ${d.vendedor.apellidos}</td>`);
						}

						$(row).find('.custom-table tbody').append(tr);
					});

					$("#contenedor-campos").append(row);

					$.ajax({
						type: "GET",
						url: "api/usuarios?tipo=TRANSPORTISTA",
						dataType: "json",
						beforeSend: function(request){
							request.setRequestHeader("Authorization", "Bearer " + token);
							request.setRequestHeader("rol", "ADMIN");
						},
						success: function (transportistas) {
							transportistas.forEach(t => {
								$("#contenedor-campos #transportistas").append(new Option(t.nombres + ' ' + t.apellidos, t.id));
							});
						}
					});
				}
			});

			function constructDatos(label, value, procesarValue){
				if(procesarValue != null){
					value = procesarValue(value);
				}  

				return `
					<div class="col-3 modal-info">
						<label for="">${label}</label>
						<label>${value}</label>
					</div>`;
			}

			function pipeEstado(estado){
				switch(estado){
					case 'WAITING': return 'En espera';
					case 'IN_PROGRESS': return 'En progreso';
					case 'ENTREGADA': return 'Entregado';
				}
			}

			function renderMotorizado(id, estado, datosMotorizado){
				if(estado == "WAITING"){
					let component = `
					<div class="col-3 modal-info" id="select-trans">
						<label for="">Nombre</label>
						<select style="width: 100%" id="transportistas">
							<option value="0">Transportista</option>
						</select>
					</div>
					`;

					return component;
				}
				else{
					let component = `
						${constructDatos("Transportista", datosMotorizado.nombres + ' ' + datosMotorizado.apellidos)}
						${constructDatos("Celular Transport.", datosMotorizado.celular)}
					`;

					return component;
				}
			}

			$("#contenedor-campos").on('change', '#transportistas', function(e){
				$("#idTransportista").val($(this).val());
			});
		});

		$("#filter-pedidos").change(function(e){
			const id = $(this).val();

			switch(id){
				case '0': window.location.assign('?type=all');break;
				case '1': window.location.assign('?type=pendientes');break;
				case '2': window.location.assign('?type=progreso');break;
				case '3': window.location.assign('?type=finalizado');break;
			}
		});
	});
</script>
@endsection