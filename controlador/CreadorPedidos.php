<?php
require_once "../ini.php";

if (!isset($_POST['value'])) {
	$url = array("vista/view_crear_pedido.php");
	$parametros = array("TABLA" => "CrearPedido", "TITULO" => "CrearPedido");
	echo Disenio::HTML($url, $parametros);
} else {

	$tabla = $_POST['value'];
	$instanc_tabla = new $tabla();
	$parametro = $instanc_tabla->dameComboBoxCrear();
	echo $parametro;
}
?>