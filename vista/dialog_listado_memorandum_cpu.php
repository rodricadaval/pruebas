<?php
require_once "../ini.php";

$parametros = array();

if (isset($_POST['action']) && $_POST['action'] == "ver_listado_para_memorandum")
{

	$monitores = "";
	$discos    = "";
	$memorias  = "";

	$id_computadora = $_POST['id_computadora'];

	$listado = Computadoras::dameListadoMemoDeCpu($id_computadora);

	//var_dump($listado);

	$url = array("vista/computadora/view_dialog_listado_memorandum_de_cpu.php");

	$parametros = array("LISTADO" => $listado, "id_computadora" => $id_computadora);

	echo Disenio::HTML($url, $parametros);
}
else
{

	$url = array("vista/computadora/view_dialog_listado_memorandum_de_cpu.php");

	$parametros = array("LISTADO" => "Hubo un error");

	echo Disenio::HTML($url, $parametros);
}
?>