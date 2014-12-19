<?php
require_once "../ini.php";

if (!isset($_POST['action'])) {
	$url = array("vista/productos/view_agregar_producto.php");
	$parametros = array("TABLA" => "Agregar Producto", "TITULO" => "Agregar");
	echo Disenio::HTML($url, $parametros);
} else {

	$inst_marcas = new Marcas();

	switch ($_POST['action']) {
		case 'agregar_monitor':
			if (!isset($_POST['tipo'])) {

				$url = array("vista/monitor/view_agregar_monitor.php");
				$select_marcas = $inst_marcas->dameSelect("monitores");
				$titulo = "Menu para agregar un Monitor";
				$parametros = array("Producto" => "Monitor", "select_marcas_monitores" => $select_marcas, "titulo" => $titulo);
				echo Disenio::HTML($url, $parametros);

			} else if ($_POST['tipo'] == "sel_modelos") {

				$inst_mon_desc = new Monitor_desc();
				echo $inst_mon_desc->dameSelect($_POST['value'], "_Monitor");

			}
			break;

		case 'agregar_computadora':
			if (!isset($_POST['tipo'])) {

				$url = array("vista/computadora/view_agregar_computadora.php");
				$select_marcas = $inst_marcas->dameSelect("computadoras");
				$select_clases = Tipos_Computadoras::dameSelect_clase();
				$titulo = "Menu para agregar una Computadora";
				$parametros = array("Producto" => "Computadora", "select_marcas_computadoras" => $select_marcas, "select_clases_Computadora" => $select_clases, "titulo" => $titulo);
				echo Disenio::HTML($url, $parametros);

			} else if ($_POST['tipo'] == "sel_modelos") {

				$inst_comp_desc = new Computadora_desc();
				echo $inst_comp_desc->dameSelect($_POST['value'], "_Computadora");
			}
			break;

		case 'agregar_memoria':
			if (!isset($_POST['tipo'])) {

				$url = array("vista/memoria/view_agregar_memoria.php");
				$select_marcas = $inst_marcas->dameSelect("memorias");
				$select_tipos = Memoria_desc::dameSelect("_memorias");
				$select_capacidades = Capacidades::dameSelect("","_memorias");
				$select_unidades = Unidades::dameSelect("","_memorias");
				$titulo = "Menu para agregar una Memoria Ram";
				$parametros = array("Producto" => "Memoria", "select_marcas_memorias" => $select_marcas, "select_tipos_memorias" => $select_tipos,"select_capacidades" => $select_capacidades,"select_unidades" => $select_unidades , "titulo" => $titulo);
				echo Disenio::HTML($url, $parametros);

			} else if ($_POST['tipo'] == "sel_velocidades") {

				$inst_vel = new Velocidades();
				echo $inst_vel->dameSelect($_POST['value_marca'],$_POST['value_tipo']);
			}
			break;

		case 'agregar_disco':
				
				$url = array("vista/disco/view_agregar_disco.php");
				$select_marcas = $inst_marcas->dameSelect("discos");
				//$select_marcas = $inst_marcas->dameSelect("discos");
				$select_capacidades = Capacidades::dameSelect("","_discos");
				$select_unidades = Unidades::dameSelect("","_discos");
				$titulo = "Menu para agregar un Disco";
				$parametros = array("Producto" => "Disco", "select_marcas_discos" => $select_marcas,"select_capacidades" => $select_capacidades,"select_unidades" => $select_unidades , "titulo" => $titulo);
				echo Disenio::HTML($url, $parametros);

				break;	

		default:
			# code...
		break;

	}
}
?>