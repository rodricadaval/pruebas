<?php
require_once "../ini.php";

$parametros = array();

if (isset($_POST['action'])) {

	$inst_usuarios = new Usuarios();
	$action = $_POST['action'];
	unset($_POST['action']);

	if (isset($_POST['password_orig'])) {
		unset($_POST['password_orig']);
	}

	switch ($action) {
		case 'modificar':

			if (isset($_POST['password'])) {
				if ($_POST['password'] != "") {
					$_POST['password'] = $_POST['nueva_password'];
				} else {
					unset($_POST['password']);
				}
				unset($_POST['nueva_password']);
				unset($_POST['conf_password']);
			}

			foreach ($_POST as $clave => $valor) {
				$parametros[$clave] = $valor;
			}

			echo $inst_usuarios->modificarDatos($parametros);
			break;
		case 'crear':

			if (isset($_POST['password']) && isset($_POST['conf_password'])) {
				unset($_POST['conf_password']);
			}

			foreach ($_POST as $clave => $valor) {
				$parametros[$clave] = $valor;
			}

			echo $inst_usuarios->crearUsuario($parametros);
			break;
		case 'eliminar':

			if ($_POST['id_usuario'] != "") {
				echo $inst_usuarios->eliminarUsuario($_POST['id_usuario']);
			}
			break;

		case 'buscar_area':

			if (isset($_POST['id_usuario'])) {
				$inst_usuarios = new Usuarios();
				echo $inst_usuarios->dame_id_area($_POST['id_usuario']);
			}
			break;
		default:
			break;

	}
} else {
	$archivos = array("vista/view_usuarios.php");
	$parametros = array("TABLA" => "Usuarios", "");
	echo Disenio::HTML($archivos, $parametros);
}

?>