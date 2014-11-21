<?php
require_once "../ini.php";

if (isset($_POST['action'])) {
	$inst_monitor = new Monitores();

	switch ($_POST['action']) {
		case 'modificar':
			unset($_POST['action']);
			$_POST['id_computadora'] = 1;
			echo $inst_monitor->modificarMonitor($_POST);
			break;

		case 'eliminar':
			unset($_POST['action']);
			echo $inst_monitor->eliminarMonitor($_POST['id_monitor']);
			break;

		default:
			# code...
			break;
	}
} else {
	$archivos = array("vista/view_monitores.php");
	$parametros = array("TABLA" => "Monitores", "");
	echo Disenio::HTML($archivos, $parametros);
}
?>