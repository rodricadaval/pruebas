<?php
require '../ini.php';

if (isset($_POST['tipo'])) {
	foreach ($_POST as $campo => $valor) {
		$datos[$campo] = $valor;
	}
	//var_dump($datos);
	return Vinculos::crearVinculo($datos);
}
?>
