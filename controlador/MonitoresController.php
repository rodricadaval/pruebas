<?php
require_once "../ini.php";

$archivos = array("vista/view_monitores.php");
$parametros = array("TABLA" => "Monitores", "");
echo Disenio::HTML($archivos, $parametros);

?>