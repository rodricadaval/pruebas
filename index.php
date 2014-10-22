<?php
require_once 'ini.php';
include 'logueo/chequeo_login.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<link href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"> 
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	
	<title>Inicio</title>
</head>
<body>

	<div class="as_wrapper">
	<h1>Bienvenido <b><?php echo ucfirst($_SESSION['username']); ?></b><span class="logout" id="logout">Desconectarse</span></h1>
	</div>

<div class="contenedor">

<table cellpadding="0" cellspacing="0" border="0" class="display">
<tr><th>Bases de datos</th></tr>
<tr><td><a href="controlador/UsuariosController.php" class="test" id="link_usuarios">ABM Usuarios</a></td></tr>
<tr><td><a href="controlador/MarcasController.php" class="test" id="link_marcas">ABM Marcas</a></td></tr>
<tr><td><a href="controlador/DepositosController.php" class="test" id="link_depositos">ABM Depósitos</a></td></tr>
<tr><td><a href="controlador/PedidosController.php" class="test" id="link_pedidos">ABM Pedidos</a></td></tr>
<tr><td><a href="controlador/InsumosController.php" class="test" id="link_insumos">ABM Insumos</a></td></tr>
<tr><td><a href="controlador/StockController.php" class="test" id="link_stock">ABM Stock</a></td></tr>
<tr><td><a href="controlador/PermisosController.php" class="test" id="link_permisos">ABM Permisos</a></td></tr>
<tr><td><a href="controlador/AreasController.php" class="test" id="link_areas">ABM Areas</a></td></tr>
</table>

</br>

<div id="contenedorPpal">
</div>
</div>
<script type="text/javascript">

	$("#logout").on('click',function(){
        $.ajax({
                url : 'logueo/logout.php',
                method: 'get',
                complete: function(){window.location = "logueo/login.php"}
        });
	});

	$(".test").on('click',function(event){
		event.preventDefault();
		$("#contenedorPpal").load($(this).attr("href"));

	});
</script>
</body>
</html>
