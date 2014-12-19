<?php
require_once "../ini.php";

if (isset($_POST['action'])) {
	$inst_disco = new Discos();

	switch ($_POST['action']) {
		case 'modificar':
			unset($_POST['action']);
			$_POST['id_usuario'] = Usuarios::getIdByNombre($_POST['nombre_usuario']);
			if(isset($_POST['asing_usr']) && $_POST['asing_usr'] == "yes"){
				$_POST['id_cpu'] = $_POST['id_computadora'];
				unset($_POST['id_computadora']);
				unset($_POST['asing_usr']);
				echo Vinculos::modificarDatos($_POST);
			}
			else if(isset($_POST['asing_cpu']) && $_POST['asing_cpu'] == "yes"){
				$_POST['id_cpu'] = $_POST['id_computadora'];
				unset($_POST['id_computadora']);
				unset($_POST['asing_cpu']);
				echo Vinculos::cambiarCpu($_POST);
			}
			else if(isset($_POST['asing_sector']) && $_POST['asing_sector'] == "yes") {
				unset($_POST['asing_sector']);
				$_POST['id_sector'] = $_POST['area'];
				unset($_POST['area']);
				echo Vinculos::cambiarSector($_POST);
			}
			else{
				$_POST['id_cpu'] = Computadoras::getIdBySerie($_POST['cpu_serie']);
				unset($_POST['cpu_serie']);
			    unset($_POST['nombre_usuario']);
				echo Vinculos::modificarDatos($_POST);
			}
			break;

		case 'eliminar':
			unset($_POST['action']);
			echo $inst_disco->eliminarLogico($_POST);
			break;

		case 'liberar':
			unset($_POST['action']);
			echo $inst_disco->liberar($_POST['id_disco']);
			break;


		default:
			# code...
			break;
	}
} else {
	$archivos = array("vista/disco/view_discos.php");
	$parametros = array("TABLA" => "Discos", "");
	echo Disenio::HTML($archivos, $parametros);
}
?>