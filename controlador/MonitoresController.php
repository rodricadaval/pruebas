<?php
require_once "../ini.php";

$archivos = array("vista/view_monitores.php");
$parametros = array("TABLA" => "Monitor", "");
echo Disenio::HTML($archivos, $parametros);

?>