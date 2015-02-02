<?php
require_once '../ini.php';

$sql = "
	select
		nombre_apellido
	from
		system.usuarios
	where
		estado = 1 and
		(
			lower(nombre_apellido) like lower('%".$_POST['term']."%')
			OR
			lower(usuario) like lower('%".$_POST['term']."%')
		)";
//$sql .= $_POST['id_deposito'] != 1 ? " and area = " . $_POST['id_deposito'] : '';

$arrayCompleto = BDD::getInstance()->query($sql)->get_listado();

echo json_encode($arrayCompleto);

?>