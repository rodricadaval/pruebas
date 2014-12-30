<?php
require_once "../ini.php";

if (!isset($_POST['action'])) {
	$url = array("vista/stock/view_stock_productos.php");
	if(isset($_POST['vista'])){
		$parametros = array("TABLA" => "Productos en Stock", "TITULO" => "Stock", "vista" => $_POST['vista']);	
	}
	else{
		$parametros = array("TABLA" => "Productos en Stock", "TITULO" => "Stock", "vista" => "nada");	
	}	
	echo Disenio::HTML($url, $parametros);
} else {

	switch ($_POST['action']) {
		case 'ver_monitores':

				$archivos = array("vista/monitor/view_stock_monitores.php");
				$parametros = array("TABLA" => "Monitores", "");
				echo Disenio::HTML($archivos, $parametros);

			break;

		case 'ver_computadoras':

				$archivos = array("vista/computadora/view_stock_computadoras.php");
				$parametros = array("TABLA" => "Computadoras", "");
				echo Disenio::HTML($archivos, $parametros);
		
			break;

		case 'ver_memorias':

				$archivos = array("vista/memoria/view_stock_memorias.php");
				$parametros = array("TABLA" => "Memorias", "");
				echo Disenio::HTML($archivos, $parametros);
			
			break;

		case 'ver_discos':
				
				$archivos = array("vista/disco/view_stock_discos.php");
				$parametros = array("TABLA" => "Discos", "");
				echo Disenio::HTML($archivos, $parametros);

				break;

		case 'ver_impresoras':
				
				$archivos = array("vista/impresora/view_stock_impresoras.php");
				$parametros = array("TABLA" => "Impresoras", "");
				echo Disenio::HTML($archivos, $parametros);

				break;		

		default:
			# code...
		break;

	}
}
?>