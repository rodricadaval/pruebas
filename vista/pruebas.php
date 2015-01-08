<?php require_once "../ini.php";

$datos['tipo'] = "Router";
$datos['id_marca'] = 14;
$datos['modelo'] = "E2000";
$clase = $datos['tipo'] . "_desc";
$inst_clase = new $clase();
$inst_clase->agregar_marca_y_modelo($datos);	

?>
