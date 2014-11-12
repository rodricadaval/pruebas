<?php
require_once "../ini.php";

$archivos   = array("vista/view_permisos.php");
$parametros = array("TABLA" => "Permisos", "");
echo Disenio::HTML($archivos, $parametros);

?>