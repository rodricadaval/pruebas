<?php
require_once "../ini.php";

$parametros = array();

if (isset($_POST['action'])) {

	$inst_computadoras = new Computadoras();
	$action = $_POST['action'];
	unset($_POST['action']);

	switch ($action) {
		case 'crear':

			break;

		case 'modificar':

			foreach ($_POST as $clave => $valor) {
				$parametros[$clave] = $valor;
			}

			echo $inst_computadoras->modificar($parametros);

			break;

		case 'eliminar':
			echo $inst_computadoras->eliminar($_POST['id_computadora']);
			break;

		case 'buscar_area':
			if (isset($_POST['num_serie'])) {
				$inst_cpu = new Computadoras();
				$id_vinc = $inst_cpu->getIdVinculoBySerie($_POST['num_serie']);
				echo Vinculos::getIdSector($id_vinc);
			}
			break;

		default:
			# code...
			break;
	}
} else {

	$archivos = array("vista/view_computadoras.php");
	$parametros = array("TABLA" => "Computadoras", "");
	echo Disenio::HTML($archivos, $parametros);
}
?>