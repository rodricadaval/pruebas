<?php
require_once "../ini.php";

if (!isset($_POST['tipo'])) {
	$archivos = array("vista/view_monitores.php");
	$parametros = array("TABLA" => "Monitores", "");
	echo Disenio::HTML($archivos, $parametros);
} else {
	if ($_POST['tipo'] == "sel_depositos") {
		echo Areas::dameSelect($_POST['value']);
	} elseif ($_POST['tipo'] == "sel_usuarios") {
		echo Usuarios::dameSelect($_POST['value']);
	}
}

?>