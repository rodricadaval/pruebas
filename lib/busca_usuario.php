<?php
require_once '../ini.php';

$sql = "select * from system.usuarios where lower(nombre_apellido) = lower('{$_POST['nombre_usuario']}') and estado = 1 ";

if (BDD::getInstance()->query($sql)->get_count()) {
	echo "true";
} else {
	echo "false";
}

?>