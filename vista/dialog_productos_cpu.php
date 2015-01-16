<?php 
require_once "../ini.php";

$parametros = array();

if(isset($_POST['action']) && $_POST['action'] == "ver_productos"){
	
	$monitores = "";
	$discos = "";
	$memorias = "";

	$id_cpu = $_POST['id_computadora'];
	$monitores = Monitores::dameListaDeCpu($id_cpu);
	$memorias = Memorias::dameListaDeCpu($id_cpu);
	$discos = Discos::dameListaDeCpu($id_cpu);

	$url = array("vista/computadora/view_dialog_productos_de_cpu.php");

	$parametros = array("Monitor" => "Monitor", "monitores" => $monitores, "memorias" => $memorias, "discos" => $discos);

	echo Disenio::HTML($url, $parametros);
}
else{

	$url = array("vista/computadora/view_dialog_productos_de_cpu.php");

	$parametros = array("Monitores" => "Monitor no entro");

	echo Disenio::HTML($url, $parametros);
}
?>