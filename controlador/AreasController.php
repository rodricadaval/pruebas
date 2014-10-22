<?php 
require_once "../ini.php";

$archivos = array("vista/view_areas.php");
//$parametros = array("MODULO" => "Areas", "OTROS" => "LosOtrosValores");
$parametros = array("TABLA" => "Areas","");
echo Disenio::HTML($archivos,$parametros);

?>