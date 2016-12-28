<?php
require_once "../ini.php";

if (isset($_POST['action']))
{
	$action = $_POST['action'];
	$id_tablet = $_POST['ID'];
	switch ($action)
	{
		case 'descripcion':
		$descripcion = Tablets::getDescripcion($id_tablet);
		$url   = array("vista/tablet/view_dialog_descripcion_tablet.php");		
		$parametros = array("id_tablet" => $id_tablet,"descripcion" => $descripcion);
		echo Disenio::HTML($url, $parametros);
		break;

		case 'agregar_desc':
		echo Tablets::setDescripcion($_POST['id_tablet'],$_POST['descripcion']);
		break;

		case 'num_serie':
		$num_serie = Tablets::getNumSerie($id_tablet);
		$url = array("vista/tablet/view_dialog_num_serie_tablet.php");
		$parametros = array('id_tablet' => $id_tablet,'num_serie' => $num_serie);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'agregar_num_serie':
		echo Tablets::setNumSerie($_POST['id_tablet'],$_POST['num_serie']);
		break;

		case 'dar_baja':
		$url = array("vista/tablet/view_dialog_dar_baja_tablet.php");
		$parametros = array('id_tablet' => $id_tablet);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'detalle': 
		$url = array("vista/tablet/view_dialog_detalle_tablet.php");
		$parametros = Tablets::getComponentes($id_tablet);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'baja':
		echo Tablets::darDeBaja($_POST['id_tablet']);
		break;

		case 'sector':
		$sector = Tablets::getSector($id_tablet);
		$url = array("vista/tablet/view_dialog_sector_tablet.php");
		$parametros = array('id_tablet' => $id_tablet,'sector' => $sector);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'cambiar_sector':
		echo Tablets::setSector($_POST['id_tablet'],$_POST['id_sector']);
		break;

		case 'usuario':
		$usuario = Tablets::getUsuario($id_tablet);
		$url = array("vista/tablet/view_dialog_modif_usuario_tablet.php");
		$parametros = array('id_tablet' => $id_tablet,'nombre_apellido' => $usuario['nombre_apellido'],'id_usuario' => $usuario['id_usuario'],'sector' => $usuario['sector']);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'cambiar_usuario':
		var_dump($_POST['nombre_usuario']);
		echo Tablets::setUsuario($_POST['id_tablet'],$_POST['nombre_usuario']);
		break;

		case 'desasignar':
		$url = array("vista/tablet/view_dialog_desasignar_tablet.php");
		$parametros = array('id_tablet' => $id_tablet);
		echo Disenio::HTML($url,$parametros);
		break;

		case 'liberar':
		echo Tablets::setLibre($_POST['id_tablet']);
		break;

		default:
		//Code
		break;
	}
}
else
{
	$archivos   = array("vista/tablet/view_tablets.php");
	$parametros = array("TABLA" => "Tablets", "");
	echo Disenio::HTML($archivos, $parametros);
}

?>