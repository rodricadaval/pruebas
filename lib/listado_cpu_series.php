<?php
require_once '../ini.php';

$sql = "
	select
		num_serie
	from
		system.computadoras
	where
		estado = 1 and
			lower(num_serie) like lower('%" . $_POST['term'] . "%')
		";

$arrayCompleto = BDD::getInstance()->query($sql)->get_listado();

echo json_encode($arrayCompleto);

?>