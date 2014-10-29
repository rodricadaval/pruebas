<?php
require_once "../ini.php";

$archivos   = array("vista/view_stock.php");
$parametros = array("TABLA" => "Stock", "");
echo Disenio::HTML($archivos, $parametros);

?>