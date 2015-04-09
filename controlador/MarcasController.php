<?php
require_once "../ini.php";

if (isset($_POST['action']))
{

	$inst_marcas = new Marcas();
	$action = $_POST['action'];
	unset($_POST['action']);

	switch ($action)
	{

		case 'modificar':

			foreach ($_POST as $clave => $valor)
		{
				$parametros[$clave] = $valor;
			}

			echo $inst_marcas->modificarDatos($parametros);

			break;

		case 'eliminar':

			echo $inst_marcas->eliminar($_POST['id_marca']);
			break;

		default:
			# code...
			break;
	}
}
else
{
	$archivos   = array("vista/marca/view_marcas.php");
	$parametros = array("TABLA" => "Marcas", "");
	echo Disenio::HTML($archivos, $parametros);
}
?>