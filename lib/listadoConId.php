<?php
require_once '../ini.php';

$sql = "
	select
		nombre_apellido, id_usuario
	from
		system.usuarios
	where
		estado = 1";

$arrayCompleto = BDD::getInstance()->query($sql)->_fetchAll();

$stringDev = "";

var_dump($arrayCompleto);
echo json_encode($arrayCompleto);

?>