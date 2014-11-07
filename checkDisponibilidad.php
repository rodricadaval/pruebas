<?php
include 'ini.php';// include the library for database connection

if (isset($_POST['action']) && $_POST['action'] == 'chequeo') {// Check the action
	$username = htmlentities($_POST['username']);// Get the username
	$inst_usuario = new Usuarios();
	$inst_bdd = $inst_usuario->chequeoExistenciaUsuario($username);
	if ($inst_bdd->get_count() == 0) {// Si no existe un usuario
		echo "true";
	} else {
		echo "false";
	}
} else {echo "false";}
?>