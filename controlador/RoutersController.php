<?php
require_once "../ini.php";

if (isset($_POST['action'])) {
	$inst_router = new Routers();

	switch ($_POST['action']) {
		case 'modificar':
			unset($_POST['action']);
			$_POST['id_usuario'] = Usuarios::getIdByNombre($_POST['nombre_usuario']);
			if(isset($_POST['asing_sector']) && $_POST['asing_sector'] == "yes") {
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

		case 'agregar_desc':
			echo $inst_router->agregarDescripcion($_POST);
			break;	

		case 'modif_ip':
			echo $inst_router->cambiarIp($_POST);
			break;		

		case 'eliminar':
			unset($_POST['action']);
			echo $inst_router->eliminarLogico($_POST);
			break;

		default:
			# code...
			break;
	}
} else {
	$archivos = array("vista/router/view_routers.php");
	$parametros = array("TABLA" => "Routers", "");
	echo Disenio::HTML($archivos, $parametros);
}
?>