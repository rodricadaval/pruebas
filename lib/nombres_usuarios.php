<?php require_once '../ini.php';

$usuarios = new Usuarios();
$vector = $usuarios->dameNombres();

$array = array();

foreach ($vector as $key => $value) {
	array_push($array, $value['nombre_apellido']);
}

echo json_encode($array);
?>