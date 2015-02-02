<?php
require_once "../ini.php";

if (isset($_POST['action']))
{
	$inst_impresora = new Impresoras();
	$inst_vinc      = new Vinculos();

	switch ($_POST['action'])
	{
		case 'modificar':
			unset($_POST['action']);
			$_POST['id_usuario'] = Usuarios::getIdByNombre($_POST['nombre_usuario']);
			if (isset($_POST['asing_sector']) && $_POST['asing_sector'] == "yes")
		{
				unset($_POST['asing_sector']);
				$_POST['id_sector'] = $_POST['area'];
				unset($_POST['area']);
				echo $inst_vinc->cambiarSector($_POST);
			}
		else
		{
				$_POST['id_cpu'] = Computadoras::getIdBySerie($_POST['cpu_serie']);
				unset($_POST['cpu_serie']);
				unset($_POST['nombre_usuario']);
				echo $inst_vinc->modificarDatos($_POST);
			}
			break;

		case 'agregar_desc':
			echo $inst_impresora->agregarDescripcion($_POST);
			break;

		case 'modif_ip':
			echo $inst_impresora->cambiarIp($_POST);
			break;

		case 'eliminar':
			unset($_POST['action']);
			echo $inst_impresora->eliminarLogico($_POST);
			break;

		default:
			# code...
						break;
	}
}
else
{
	$archivos   = array("vista/impresora/view_impresoras.php");
	$parametros = array("TABLA" => "Impresoras", "");
	echo Disenio::HTML($archivos, $parametros);
}
?>