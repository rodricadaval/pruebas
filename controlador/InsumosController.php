<?php 
require_once "../ini.php";

$archivos = array("vista/view_insumos.php");
$parametros = array("TABLA" => "Insumos","");
echo Disenio::HTML($archivos,$parametros);

?>