<?php
require_once "../ini.php";

$parametros = array();

if (isset($_POST['action']))
{

	$inst_usuarios = new Usuarios();
	$action = $_POST['action'];
	unset($_POST['action']);

	switch ($action)
	{
		case 'modificar':

		foreach ($_POST as $clave => $valor)
		{
			$parametros[$clave] = $valor;
		}
		echo $inst_usuarios->modificarDatos($parametros);
		break;

		case 'crear':

		unset($_POST['con_productos']);

		foreach ($_POST as $clave => $valor)
		{
			$parametros[$clave] = $valor;
		}
		echo $inst_usuarios->crearUsuario($parametros);

		break;

		case 'eliminar':

		if ($_POST['id_usuario'] != "")
		{
			echo $inst_usuarios->eliminarUsuario($_POST['id_usuario']);
		}
		break;

		case 'buscar_area':

		if (isset($_POST['nombre_usuario']))
		{
			$inst_usuarios = new Usuarios();
			$id_usuario    = $inst_usuarios->getIdByNombre($_POST['nombre_usuario']);
			echo $inst_usuarios->dame_id_area($id_usuario);
		}
		break;

		case 'descripcion_area':
		if (isset($_POST['id_usuario']))
		{
			$inst_usuarios = new Usuarios();
			echo $inst_usuarios->dame_descripcion_area($_POST['id_usuario']);
		}
		break;

		case 'nombre_sector':
		if (isset($_POST['nombre_usuario']))
		{
			$inst_usuarios = new Usuarios();
			$id_usuario    = $inst_usuarios->getIdByNombre($_POST['nombre_usuario']);
			$id_area = $inst_usuarios->dame_id_area($id_usuario);
			echo $inst_usuarios->dame_nombre_area($id_area);
		}
		break;

		default:
		break;
	}
}
else
{
	$archivos   = array("vista/usuario/view_usuarios.php");
	$parametros = array("TABLA" => "Usuarios", "");
	echo Disenio::HTML($archivos, $parametros);
}

?>