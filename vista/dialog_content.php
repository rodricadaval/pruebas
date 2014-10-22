<?php 
require_once "../ini.php";

$url = array("vista/view_dialog_".$_POST['queSos'].".php");

$tabla = $_POST['TablaPpal'];

unset($_POST['queSos']);
unset($_POST['TablaPpal']);

$inst_tabla = new $tabla();

$datos_tabla = $inst_tabla->getById($_POST['ID']);

$parametros = array();

foreach($_POST as $key => $value){

	if(substr($key,0,6) == "select"){
		$inst_clase = new $value();
		$tipo = strtolower(substr($value,0,-1));
		if($tipo == "permiso"){
			$tipo = $tipo ."s";
		}
		$parametros[$key] = $inst_clase->dameSelect($datos_tabla[$tipo]);
	}
	else{$parametros[$key] = $value;}
}

$parametros = array_merge($datos_tabla, $parametros);
echo Disenio::HTML($url,$parametros);
?>