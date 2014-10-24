<?php
include 'ini.php'; // include the library for database connection

if(isset($_POST['action']) && $_POST['action'] == 'chequeo'){ // Check the action
	$username 		= htmlentities($_POST['username']); // Get the username
	$inst_usuario	= new Usuarios();
	$inst_bdd 		= $inst_usuario->chequeoExistenciaUsuario($username);
	$num_rows		= $inst_bdd->get_count(); // Get the number of rows
	if($num_rows <= 0){ // If no users exist with posted credentials print 0 like below.
		echo 0;
	}
	else{
		echo 1;
	}
}
?>