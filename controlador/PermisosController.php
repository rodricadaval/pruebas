<?php
require_once "../ini.php";

$archivos   = array("vista/permiso/view_permisos.php");
$parametros = array("TABLA" => "Permisos", "");
echo Disenio::HTML($archivos, $parametros);

?>