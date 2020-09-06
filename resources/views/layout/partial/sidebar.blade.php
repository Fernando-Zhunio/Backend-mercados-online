<aside class="affix">
	<a class="opcion pedidos" href="{{action('HomeController@index')}}">
		<i class="fas fa-receipt" style="width: 25px; text-align: center"></i> 
		<span>Pedidos</span>
	</a>
	<a class="opcion mercados" href="{{action('MercadoController@indexPage')}}">
		<i class="fas fa-store" style="width: 25px;text-align: center"></i> 
		<span>Mercados</span>
	</a>
	<a class="opcion mercados" href="{{action('TiendaController@indexPage')}}">
		<i class="fas fa-cash-register" style="width: 25px;text-align: center"></i> 
		<span>Negocios</span>
	</a>
	<a class="opcion usuarios" href="{{ action('UsuarioController@indexPage') }}">
		<i class="fas fa-users" style="width: 25px;text-align: center"></i> 
		<span>Usuarios</span>
	</a>
	<a class="opcion usuarios" href="{{ action('PuestoController@indexPage') }}">
		<i class="fas fa-shopping-basket" style="width: 25px;text-align: center"></i> 
		<span>Puestos</span>
	</a>
	<a class="opcion usuarios" href="{{ action('ProductoController@indexPage') }}">
		<i class="fas fa-apple-alt" style="width: 25px;text-align: center"></i> 
		<span>Productos</span>
	</a>
</aside>