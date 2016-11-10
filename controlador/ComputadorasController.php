<?php
require_once "../ini.php";

$parametros = array();

if (isset($_POST['action']))
{

	$inst_computadoras = new Computadoras();
	$inst_vinc = new Vinculos();
	$action    = $_POST['action'];
	unset($_POST['action']);

	switch ($action)
	{
		case 'crear':

			break;

		case 'modificar':

			if (isset($_POST['cuestion']))
		{
				switch ($_POST['cuestion'])
			{
				case 'tipo':
						unset($_POST['cuestion']);
						echo $inst_computadoras->cambiarTipo($_POST);
						break;

				case 'sector':
						unset($_POST['cuestion']);
						$_POST['id_sector'] = $_POST['area'];
						unset($_POST['area']);
						if ($_POST['en_conjunto'] == "SI")
				{
							unset($_POST['en_conjunto']);
							echo $inst_computadoras->modificarSectorConAsignados($_POST);
						}
				else if ($_POST['en_conjunto'] == "NO")
				{
							unset($_POST['en_conjunto']);
							echo $inst_computadoras->modificarSectorSinAsignados($_POST);
						}
						break;

				case 'usuario':
						unset($_POST['cuestion']);
						$_POST['id_sector'] = $_POST['area'];
						unset($_POST['area']);
						$_POST['id_usuario'] = Usuarios::getIdByNombre($_POST['nombre_usuario']);
						unset($_POST['nombre_usuario']);
						if ($_POST['en_conjunto'] == "SI")
				{
							unset($_POST['en_conjunto']);
							echo $inst_computadoras->modificarUsuarioConAsignados($_POST);
						}
				else if ($_POST['en_conjunto'] == "NO")
				{
							unset($_POST['en_conjunto']);
							echo $inst_computadoras->modificarUsuarioSinAsignados($_POST);
						}

						break;

				default:
						unset($_POST['cuestion']);
						foreach ($_POST as $clave => $valor)
				{
							$parametros[$clave] = $valor;
						}
						echo $inst_computadoras->modificar($parametros);
						break;
				}
			}
			break;

		case 'eliminar':

			echo $inst_computadoras->eliminarLogico($_POST);
			break;

		case 'agregar_desc':

			echo $inst_computadoras->agregarDescripcion($_POST);
			break;

		case 'buscar_area':
			if (isset($_POST['num_serie']))
		{
				$id_vinc = $inst_computadoras->getIdVinculoBySerie($_POST['num_serie']);
				echo $inst_vinc->getIdSector($id_vinc);
			}
			break;

		case 'cpus_del_usuario':
			$id_usuario = Usuarios::getIdByNombre($_POST['nombre_usuario']);
			echo $inst_computadoras->dameSelectDeUsuario($id_usuario, $_POST['extra_id_select']);

			break;

		case 'chequear_slots':
			echo $inst_computadoras->tieneSlotsLibres($_POST['id_cpu']);
			break;

		case 'chequear_espacio_mem':
			echo $inst_computadoras->tieneEspacioMemLibre($_POST);
			break;

		case 'buscarIdPorNumSerie':
		echo $inst_computadoras->getIdBySerie($_POST['num_serie']);
		break;

		case 'liberar':
			if ($_POST['en_conjunto'] == "SI")
		{
				unset($_POST['en_conjunto']);
				echo $inst_computadoras->quitarUsuarioConAsignados($_POST);
			}
		else if ($_POST['en_conjunto'] == "NO")
		{
				unset($_POST['en_conjunto']);
				echo $inst_computadoras->quitarUsuarioSinAsignados($_POST);
			}
			break;

		default:
			# code...
						break;
	}
}
else
{

	$archivos   = array("vista/computadora/view_computadoras.php");
	$parametros = array("TABLA" => "Computadoras", "");
	echo Disenio::HTML($archivos, $parametros);
}
?>