<?php
require_once "../ini.php";

if (isset($_POST['action'])) {
	$inst_monitor = new Monitores();

	switch ($_POST['action']) {
		case 'modificar':
			unset($_POST['action']);
			$_POST['id_usuario'] = Usuarios::getIdByNombre($_POST['nombre_usuario']);
			if(isset($_POST['asing_usr']) && $_POST['asing_usr'] == "yes"){
				$_POST['id_cpu'] = $_POST['id_computadora'];
				unset($_POST['id_computadora']);
				unset($_POST['asing_usr']);
			}
			else{
				$_POST['id_cpu'] = Computadoras::getIdBySerie($_POST['cpu_serie']);
				unset($_POST['cpu_serie']);
			}
			unset($_POST['nombre_usuario']);
			echo Vinculos::modificarDatos($_POST);
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