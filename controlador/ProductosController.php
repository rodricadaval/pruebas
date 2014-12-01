<?php
require_once "../ini.php";

if (!isset($_POST['action'])) {
	$url = array("vista/view_agregar_producto.php");
	$parametros = array("TABLA" => "Agregar Producto", "TITULO" => "Agregar");
	echo Disenio::HTML($url, $parametros);
} else {
	switch ($_POST['action']) {
		case 'view_agregar_monitor':
			if (!isset($_POST['tipo'])) {

				$url = array("vista/view_agregar_monitor.php");
				$inst = new Marcas;
				$select = $inst->dameSelect("_Monitor");
				$titulo = "Menu para agregar un Monitor";
				$parametros = array("Producto" => "Monitor", "select_marcas_Monitor" => $select, "titulo" => $titulo);
				echo Disenio::HTML($url, $parametros);

			} else if ($_POST['tipo'] == "sel_modelos") {

				$inst = new Monitor_desc();
				echo $inst->dameSelect($_POST['value'], "_Monitor");

			}

			break;
		case 'view_agregar_computadora':
			if (!isset($_POST['tipo'])) {

				$url = array("vista/view_agregar_computadora.php");
				$inst = new Marcas;
				$select = $inst->dameSelect("_Computadora");
				$select_clases = Tipos_Computadoras::dameSelect_clase();
				$titulo = "Menu para agregar una Computadora";
				$parametros = array("Producto" => "Computadora", "select_marcas_Computadora" => $select, "select_clases_Computadora" => $select_clases, "titulo" => $titulo);
				echo Disenio::HTML($url, $parametros);

			} else if ($_POST['tipo'] == "sel_modelos") {

				$inst = new Computadora_desc();
				echo $inst->dameSelect($_POST['value'], "_Computadora");
			}
		default:
			# code...
		break;

	}
}

/*else if ($_POST['tipo'] == "sel_depositos") {

$inst = new Areas();
if (isset($_POST['value'])) {
echo $inst->dameSelect($_POST['value'], $_POST['queSos']);
} else {
echo $inst->dameSelect("", $_POST['queSos']);
}

} else if ($_POST['tipo'] == "sel_usuarios") {
$inst = new Usuarios();
echo $inst->dameSelect("", $_POST['queSos']);

} else if ($_POST['tipo'] == "sel_computadoras") {
$inst = new Computadoras();
echo $inst->dameSelect("", $_POST['queSos']);
}
 */
?>