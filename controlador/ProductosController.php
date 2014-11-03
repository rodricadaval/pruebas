<?php
require_once "../ini.php";

if (!isset($_POST['action'])) {
	$url = array("vista/view_agregar_producto.php");
	$parametros = array("TABLA" => "Agregar Producto", "TITULO" => "Agregar");
	echo Disenio::HTML($url, $parametros);
} else {

	switch ($_POST['tipo']) {
		case 'Monitor':
			if ($_POST['action'] == "sel_marcas") {
				$inst = new Marcas;
				echo $inst->dameSelect();
			} else if ($_POST['action'] == "sel_modelos") {
				$inst = new Monitor_desc();
				var_dump("Entra aca");
				echo $inst->dameSelect();
			}
			break;

		default:
			# code...
			break;

	}
}
?>