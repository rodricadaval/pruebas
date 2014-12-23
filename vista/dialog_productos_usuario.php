<?php 
require_once "../ini.php";

$parametros = array();

if(isset($_POST['action']) && $_POST['action'] == "ver_productos"){
	
	$monitores = "";
	$discos = "";
	$memorias = "";
	$computadoras = "";

	$inst_usuario = new Usuarios();
	$id_usuario = $inst_usuario->getIdByNombre($_POST['usuario']);
	$monitores = Monitores::dameListaDeUsuario($id_usuario);
	$memorias = Memorias::dameListaDeUsuario($id_usuario);
	$computadoras = Computadoras::dameListaDeUsuario($id_usuario);
	$discos = Discos::dameListaDeUsuario($id_usuario);

	$url = array("vista/usuario/view_dialog_productos_de_usuario.php");

	$parametros = array("Monitor" => "Monitor", "monitores" => $monitores, "memorias" => $memorias, "computadora" => $computadoras, "discos" => $discos);

	echo Disenio::HTML($url, $parametros);
}
else{

	$url = array("vista/usuario/view_dialog_productos_de_usuario.php");

	$parametros = array("Monitores" => "Monitor no entro");

	echo Disenio::HTML($url, $parametros);
}
?>