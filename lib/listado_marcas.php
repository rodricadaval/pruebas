<?php
require_once '../ini.php';

$sql = "
	select
		nombre
	from
		system.marcas
	where
		estado = 1 and
		(
			lower(nombre) like lower('%" . $_POST['term'] . "%')
		)";

$arrayCompleto = BDD::getInstance()->query($sql)->get_listado();

echo json_encode($arrayCompleto);

?>