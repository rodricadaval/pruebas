<?php
require_once "../ini.php";

$parametros = array();

if (isset($_POST['action'])) {

	$inst_areas = new Areas();
	$action = $_POST['action'];
	unset($_POST['action']);

	switch ($action) {
		case 'crear':

			break;

		case 'modificar':

			foreach ($_POST as $clave => $valor) {
				$parametros[$clave] = $valor;
			}

			echo $inst_areas->modificarDatos($parametros);

			break;

		default:
			# code...
			break;
	}
} else {

	$archivos = array("vista/area/view_areas.php");
//$parametros = array("MODULO" => "Areas", "OTROS" => "LosOtrosValores");
	$parametros = array("TABLA" => "Areas", "");
	echo Disenio::HTML($archivos, $parametros);
}
?>