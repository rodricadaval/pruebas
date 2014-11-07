<?php
require_once "../ini.php";
$url = array("vista/view_dialog_asignar_usuario.php");

switch ($_POST['tipo']) {
	case 'Monitores':
		echo Disenio::HTML($url, array(""));
		break;

	default:
		# code...
		break;
}
?>