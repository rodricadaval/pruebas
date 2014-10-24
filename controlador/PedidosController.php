<?php 
require_once "../ini.php";

$url = array("vista/view_pedidos.php");
$parametros = array("TABLA" => "Pedidos","");
echo Disenio::HTML($url,$parametros);

?>