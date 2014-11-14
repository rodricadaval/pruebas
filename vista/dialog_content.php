<?php
require_once "../ini.php";

$tabla = $_POST['TablaPpal'];

$inst_tabla = new $tabla();

$parametros = array();

if ($_POST['queSos'] == "nuevo") {

	$_POST['queSos'] = strtolower(substr($_POST['TablaPpal'], 0, -1));

	$datos_tabla = $inst_tabla->getCampos();

	$datos_tabla['nuevo'] = 1;
} else if (isset($_POST['queSos'])) {

	$datos_tabla = $inst_tabla->getById($_POST['ID']);
	$datos_tabla['nuevo'] = 0;
}

unset($_POST['TablaPpal']);

foreach ($_POST as $key => $value) {

	if (substr($key, 0, 6) == "select") {
		$inst_clase = new $value();

		switch ($_POST['queSos']) {
			case 'usuario':
				$tipo = strtolower(substr($value, 0, -1));
				if ($tipo == "permiso") {
					$tipo = $tipo . "s";
				}
				if ($datos_tabla[$tipo] != "") {
					$parametros[$key] = $inst_clase->dameSelect($datos_tabla[$tipo]);
				} else {
					$parametros[$key] = $inst_clase->dameSelect("");
				}

				break;
			case 'monitor':
				$clasePpal = new Vinculos();
				if ($value == "Areas") {
					$id = $clasePpal->getIdSector($datos_tabla['id_vinculo']);
				} else if ($value == "Usuarios") {
					$id = $clasePpal->getIdUsuario($datos_tabla['id_vinculo']);
				}
				$parametros[$key] = $inst_clase->dameSelect($id);
				break;

			default:
				# code...
				break;
		}

	} else { $parametros[$key] = $value;}
}

$parametros = array_merge($datos_tabla, $parametros);

$url = array("vista/view_dialog_" . $_POST['queSos'] . ".php");

unset($_POST['queSos']);

echo Disenio::HTML($url, $parametros);
?>