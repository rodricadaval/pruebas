<?php 
require_once "../ini.php";

$parametros = array();

if(isset($_POST['action']) && $_POST['action'] == "ver_listado_para_memorandum"){
	
	$monitores = "";
	$discos = "";
	$memorias = "";
	$computadoras = "";

	$id_usuario = $_POST['id_usuario'];
	$listado = Usuarios::dameListadoMemoDeUsuario($id_usuario);

	$url = array("vista/usuario/view_dialog_listado_memorandum_de_usuario.php");

	$parametros = array("LISTADO" => $listado);

	echo Disenio::HTML($url, $parametros);
}
else{

	$url = array("vista/usuario/view_dialog_listado_memorandum_de_usuario.php");

	$parametros = array("LISTADO" => "Hubo un error");

	echo Disenio::HTML($url, $parametros);
}
?>