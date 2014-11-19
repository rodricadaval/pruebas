<?php require_once '../ini.php';

$query = $_GET['search_term'] . '%';

$vector = BDD::getInstance()->query("select nombre_apellido,area,id_usuario from system.usuarios where lower(nombre_apellido) LIKE lower('%$query%') OR lower(usuario) LIKE lower('%$query%') ")->_fetchAll();
foreach ($vector as $fila) {
	$fila['area'] = Areas::getNombre($fila['area']);
	if ($fila['id_usuario'] == 1) {
		$fila['nombre_apellido'] = "Sin usuario";
		$fila['area'] = "";
	}
	$arrayCompleto[] = $fila;
}

echo json_encode($arrayCompleto);
?>