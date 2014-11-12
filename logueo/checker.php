<?php
include '../ini.php';// include the library for database connection

function encrypt($string) {
	return base64_encode(base64_encode(base64_encode($string)));
}

function decrypt($string) {
	return base64_decode(base64_decode(base64_decode($string)));
}

if (isset($_POST['action']) && $_POST['action'] == 'login') {// Check the action `login`
	$username = htmlentities($_POST['username']);// Get the username
	//$password 		= htmlentities(decrypt($_POST['password'])); // Get the password and decrypt it
	$password = htmlentities($_POST['password']);
	$inst_usuario = new Usuarios();
	$inst_bdd = $inst_usuario->obtenerUsuarioLogin($username, $password);
	$num_rows = $inst_bdd->get_count();// Get the number of rows
	if ($num_rows <= 0) {// If no users exist with posted credentials print 0 like below.
		echo 0;
	} else {
		$user = $inst_bdd->_fetchAll();
		// NOTE : We have already started the session in the ini.php
		$_SESSION['userid'] = $user[0]['id_usuario'];
		$_SESSION['username'] = $user[0]['usuario'];
		$_SESSION['priority'] = $user[0]['permisos'];
		echo 1;
	}
}
?>