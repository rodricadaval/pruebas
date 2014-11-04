<?php
require_once "../ini.php";

if (!isset($_POST['action'])) {
	$url = array("vista/view_agregar_producto.php");
	$parametros = array("TABLA" => "Agregar Producto", "TITULO" => "Agregar");
	echo Disenio::HTML($url, $parametros);
} else {
	switch ($_POST['action']) {
		case 'view_agregar_monitor':
			if ($_POST['tipo'] == "sel_marcas") {
				$url = array("vista/view_agregar_monitor.php");
				$inst = new Marcas;
				$select = $inst->dameSelect();
				$titulo = "Menu para agregar y asignar (si es necesario) un Monitor";
				$parametros = array("Producto" => "Monitor", "select_marcas" => $select, "titulo" => $titulo);

				echo Disenio::HTML($url, $parametros);
			} else if ($_POST['tipo'] == "sel_modelos") {

				$inst = new Monitor_desc();
				echo $inst->dameSelect($_POST['value']);
			} else if ($_POST['tipo'] == "sel_depositos") {

				$inst = new Areas();
				echo $inst->dameSelect();
			}
			break;

		default:
			# code...
			break;

	}
}
?>