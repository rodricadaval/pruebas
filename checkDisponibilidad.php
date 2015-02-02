


<?php
include 'ini.php'; // include the library for database connection

if (isset($_POST['action']) && $_POST['action'] == 'chequeo')
{
// Check the action
	$dato = htmlentities($_POST['dato']); // Get the data
	$tabla = $_POST['tabla'];
	$inst_tabla = new $tabla();
	$metodo     = "chequeoExistencia".$tabla;
	$inst_bdd   = $inst_tabla->$metodo($dato);
	if ($inst_bdd->get_count() == 0)
	{
// Si no existe un usuario
		echo "true";
	}
	else
	{
		echo "false";
	}
}
else
{
	echo "false";}
?>