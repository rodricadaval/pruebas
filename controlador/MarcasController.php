<?php
require_once "../ini.php";

$archivos   = array("vista/view_marcas.php");
$parametros = array("TABLA" => "Marcas", "");
echo Disenio::HTML($archivos, $parametros);

?>