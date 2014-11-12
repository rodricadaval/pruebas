<?php
require_once "../ini.php";

$archivos   = array("vista/view_depositos.php");
$parametros = array("TABLA" => "Depositos", "");
echo Disenio::HTML($archivos, $parametros);

?>