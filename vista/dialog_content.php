<?php
require_once "../ini.php";

$tabla = $_POST['TablaPpal'];

$inst_tabla = new $tabla();

$parametros = array();

if (isset($_POST['viene']))
{
	$datos_tabla['viene'] = $_POST['viene'];
}

if ($_POST['queSos'] == "nuevo")
{

	$_POST['queSos'] = strtolower(substr($_POST['TablaPpal'], 0, -1));

	$datos_tabla = $inst_tabla->getCampos();

	$datos_tabla['nuevo'] = 1;
}
else if (isset($_POST['queSos']))
{

	$datos_tabla = $inst_tabla->getById($_POST['ID']);
	$datos_tabla['nuevo'] = 0;
}

$vista_folder = $_POST['queSos'];

unset($_POST['TablaPpal']);

foreach ($_POST as $key => $value)
{

	if (substr($key, 0, 6) == "select")
	{
		$inst_clase = new $value();

		switch ($_POST['queSos'])
		{

			case 'usuario':
				$tipo = strtolower(substr($value, 0, -1));
				if ($tipo == "permiso")
			{
					$tipo = $tipo."s";
				}
				if ($datos_tabla[$tipo] != "")
			{
					$parametros[$key] = $inst_clase->dameSelect($datos_tabla[$tipo]);
				}
			else
			{
					$parametros[$key] = $inst_clase->dameSelect("");
				}

				break;

			case ($_POST['queSos'] == 'monitor' || $_POST['queSos'] == 'disco' || $_POST['queSos'] == 'impresora' || $_POST['queSos'] == 'router' || $_POST['queSos'] == 'switch'):

				$clasePpal = new Vinculos();

				$metodo = "dameSelect";
				$sos    = $_POST['queSos'];

				if ($value == "Areas")
			{
					if (isset($_POST['action']) && $_POST['action'] == "modif_sector")
				{
						$parametros['libre'] = $clasePpal->estaLibre($datos_tabla['id_vinculo']);
					}
					$id = $clasePpal->getIdSector($datos_tabla['id_vinculo']);
				}
			else if ($value == "Usuarios")
			{
					$id = $clasePpal->getIdUsuario($datos_tabla['id_vinculo']);
				}
			else if ($value == "Computadoras")
			{
					if (isset($_POST['action']) && $_POST['action'] == "modif_cpu")
				{
						$id = $clasePpal->getIdUsuario($datos_tabla['id_vinculo']);
						$metodo .= "DeUsuario";
						$sos .= "_".$_POST['action'];
						if ($id == 1 || $id == "")
					{
							$id  = $datos_tabla['id_vinculo'];
							$sos = "dialog_mod_cpu_sin_usr";
						}
					}
				else if (isset($_POST['action']) && $_POST['action'] == "modif_usuario")
				{
						$id = $clasePpal->getIdUsuario($datos_tabla['id_vinculo']);
						$metodo .= "DeUsuario";
					}
				else
				{
						$id = $clasePpal->getIdCpu($datos_tabla['id_vinculo']);
					}
				}

				$parametros[$key] = $inst_clase->$metodo($id, $sos);

				break;

			case 'computadora':
				$clasePpal = new Vinculos();

				$metodo = "dameSelect";

				if ($value == "Areas")
			{

					if (isset($_POST['action']) && $_POST['action'] == "modif_sector")
				{
						$parametros['libre'] = $clasePpal->estaLibre($datos_tabla['id_vinculo']);
					}
					$id = $clasePpal->getIdSector($datos_tabla['id_vinculo']);
				}
			else if ($value == "Usuarios")
			{
					$id = $clasePpal->getIdUsuario($datos_tabla['id_vinculo']);
				}
			else if ($value == "Computadoras")
			{
					$metodo .= "_button_radio_clase";
					$id = $datos_tabla['clase'];
				}
				$parametros[$key] = $inst_clase->$metodo($id, $_POST['queSos']);
				break;

			case 'memoria':

				$clasePpal = new Vinculos();

				$metodo = "dameSelect";
				$sos    = $_POST['queSos'];

				if ($value == "Areas")
			{
					if (isset($_POST['action']) && $_POST['action'] == "modif_sector")
				{
						$parametros['libre'] = $clasePpal->estaLibre($datos_tabla['id_vinculo']);
					}
					$id = $clasePpal->getIdSector($datos_tabla['id_vinculo']);
				}
			else if ($value == "Usuarios")
			{
					$id = $clasePpal->getIdUsuario($datos_tabla['id_vinculo']);
				}
			else if ($value == "Computadoras")
			{
					if (isset($_POST['action']) && $_POST['action'] == "modif_cpu")
				{
						$id = $clasePpal->getIdUsuario($datos_tabla['id_vinculo']);
						$metodo .= "DeUsuario";
						$sos .= "_".$_POST['action'];
						if ($id == 1 || $id == "")
					{
							$id  = $datos_tabla['id_vinculo'];
							$sos = "dialog_mod_cpu_sin_usr";
						}
					}
				else if (isset($_POST['action']) && $_POST['action'] == "modif_usuario")
				{
						$id = $clasePpal->getIdUsuario($datos_tabla['id_vinculo']);
						$metodo .= "DeUsuario";
					}
				else
				{
						$id = $clasePpal->getIdCpu($datos_tabla['id_vinculo']);
					}
				}

				$parametros[$key] = $inst_clase->$metodo($id, $sos);

				break;
			default:
				# code...
								break;
		}

	}
	else
	{
		 $parametros[$key] = $value;}
}

if (isset($_POST['action']))
{
	$_POST['queSos'] .= "_".$_POST['action'];
}

$parametros = array_merge($datos_tabla, $parametros);

$url = array("vista/".$vista_folder."/view_dialog_".$_POST['queSos'].".php");

unset($_POST['queSos']);

echo Disenio::HTML($url, $parametros);
?>