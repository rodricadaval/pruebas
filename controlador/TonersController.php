<?php
require_once "../ini.php";

if (isset($_POST['action']))
{
	$id_toner = $_POST['ID'];
	switch ($_POST['action'])
	{
		case 'dar_alta':
		echo Toners::darDeAlta($_POST['id_impresora_desc'],$_POST['id_area'],$_POST['cantidad']);
		break;

		case 'mostrar_descripcion':
		$descripcion = Toners::getDescripcion($id_toner);
		$url   = array("vista/toner/view_dialog_descripcion_toner.php");		
		$parametros = array("id_toner" => $id_toner,"descripcion" => $descripcion);
		echo Disenio::HTML($url, $parametros);
		break;

		case 'cambiar_descripcion':
		echo Toners::setDescripcion($id_toner,$_POST['descripcion']);
		break;

		case 'mostrar_baja':
		$url = array("vista/toner/view_dialog_dar_baja_toner.php");
		$descripcion = Toners::getDescripcion($id_toner);
		$parametros = array('id_toner' => $id_toner,'descripcion' => $descripcion);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'dar_baja':
		Toners::setDescripcion($id_toner,$_POST['descripcion']);
		echo Toners::darDeBaja($id_toner);
		break;

		case 'mostrar_area':
		$id_area = Toners::getArea($id_toner);
		$select_areas = Areas::dameSelect($id_area,'');
		$url = array("vista/toner/view_dialog_area_toner.php");
		$parametros = array('id_toner' => $id_toner,'select_areas' => $select_areas);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'cambiar_area':
		echo Toners::setArea($id_toner,$_POST['area']);
		break;

		case 'mostrar_liberar':
		$url = array("vista/toner/view_dialog_liberar_toner.php");
		$parametros = array('id_toner' => $id_toner);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'liberar':
		echo Toners::setLibre($id_toner);
		break;

		default:
		//Code
		break;
	}
}
else
{
	$archivos   = array("vista/toner/view_toners.php");
	$parametros = array("TABLA" => "Toners", "");
	echo Disenio::HTML($archivos, $parametros);
}

?>