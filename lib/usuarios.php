<?php require_once '../ini.php';

$query = $_GET['search_term'].'%';

$arrayCompleto = BDD::getInstance()->query("select nombre_apellido from system.usuarios where estado = 1 AND (lower(nombre_apellido) LIKE lower('%$query%') OR lower(usuario) LIKE lower('%$query%') )")->get_listado();

echo json_encode($arrayCompleto);
?>