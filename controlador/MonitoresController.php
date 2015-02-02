<?php
require_once "../ini.php";

if (isset($_POST['action']))
{
	$inst_monitor = new Monitores();
	$inst_vinc    = new Vinculos();

	switch ($_POST['action'])
	{
		case 'modificar':
			unset($_POST['action']);
			$_POST['id_usuario'] = Usuarios::getIdByNombre($_POST['nombre_usuario']);
			if (isset($_POST['asing_usr']) && $_POST['asing_usr'] == "yes")
		{
				$_POST['id_cpu'] = $_POST['id_computadora'];
				unset($_POST['id_computadora']);
				unset($_POST['asing_usr']);
				echo $inst_vinc->modificarDatos($_POST);
			}
		else if (isset($_POST['asing_cpu']) && $_POST['asing_cpu'] == "yes")
		{
				$_POST['id_cpu'] = $_POST['id_computadora'];
				unset($_POST['id_computadora']);
				unset($_POST['asing_cpu']);
				echo $inst_vinc->cambiarCpu($_POST);
			}
		else if (isset($_POST['asing_sector']) && $_POST['asing_sector'] == "yes")
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

		case 'eliminar':
			unset($_POST['action']);
			echo $inst_monitor->eliminarLogico($_POST);
			break;

		case 'liberar':
			unset($_POST['action']);
			echo $inst_monitor->liberarMonitor($_POST['id_monitor']);
			break;

		default:
			# code...
						break;
	}
}
else
{
	$archivos   = array("vista/monitor/view_monitores.php");
	$parametros = array("TABLA" => "Monitores", "");
	echo Disenio::HTML($archivos, $parametros);
}
?>