<?php
require_once "../ini.php";

if (isset($_POST['action']))
{
	$action = $_POST['action'];
	$id_toner = $_POST['ID'];
	switch ($action)
	{
		case 'descripcion':
		$descripcion = Toners::getDescripcion($id_toner);
		$url   = array("vista/toner/view_dialog_descripcion_toner.php");		
		$parametros = array("id_toner" => $id_toner,"descripcion" => $descripcion);
		echo Disenio::HTML($url, $parametros);
		break;

		case 'agregar_descripcion':
		echo Toners::setDescripcion($_POST['id_toner'],$_POST['descripcion']);
		break;

		case 'dar_baja':
		$url = array("vista/toner/view_dialog_dar_baja_toner.php");
		$descripcion = Toners::getDescripcion($id_toner);
		$parametros = array('id_toner' => $id_toner,'descripcion' => $descripcion);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'detalle': 
		$url = array("vista/toner/view_dialog_detalle_toner.php");
		$parametros = Toners::getComponentes($id_toner);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'baja':
		Toners::setDescripcion($_POST['id_toner'],$_POST['descripcion']);
		echo Toners::darDeBaja($_POST['id_toner']);
		break;

		case 'area':
		$area = Toners::getArea($id_toner);
		$url = array("vista/toner/view_dialog_area_toner.php");
		$parametros = array('id_toner' => $id_toner,'area' => $area);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'cambiar_sector':
		echo Toners::setSector($_POST['id_toner'],$_POST['id_sector']);
		break;

		case 'liberar':
		echo Toners::setLibre($_POST['id_toner']);
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