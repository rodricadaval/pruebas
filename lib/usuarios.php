<?php require_once '../ini.php'

$usuarios = new Usuarios();
$datos = $usuarios->dameUsuarios();

foreach ($datos as $campo => $valor) {
 	$array = new array();
 	array_push($array, $valor);
} 

?>