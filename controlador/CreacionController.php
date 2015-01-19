<?php
require '../ini.php';

if (isset($_POST['tipo'])) {
	$inst_vinc = new Vinculos();

	foreach ($_POST as $campo => $valor) {
		$datos[$campo] = $valor;
	}
	$tipo_productos = array();
	$tipo_productos = Tipo_productos::get_rel_campos();

	if (in_array($_POST['tipo'], $tipo_productos)) {

		$datos['id_tipo_producto'] = array_search($_POST['tipo'], $tipo_productos);

		Consola::mostrar($datos);

		$i = 1;

		if(isset($_POST['cant_veces']) && $_POST['cant_veces'] > 1){
			while ($i < $_POST['cant_veces']) 
			{ 
				if($inst_vinc->crearVinculo($datos, $_POST['tipo']) == "true")
					{
						$i++;
					}
				else{
					$i = $_POST['cant_veces'] + 1;
					return "false";
				} 	
			}
			unset($_POST['cant_veces']);
		}
		return $inst_vinc->crearVinculo($datos, $_POST['tipo']);
	}
}
else if($_POST['action'] == "nueva_marca"){
	unset($_POST['action']);
	switch ($_POST['tablaPpal']) {
		
		case 'Computadora':
			$url = array("vista/productos/nueva_marca_y_modelo_computadora.php");
			$select_capacidades = Capacidades::dameSelect("","_computadoras");
			$select_unidades = Unidades::dameSelect("","_computadoras");
			$parametros = array("Producto" => $_POST['tablaPpal'], "select_capacidades" => $select_capacidades,"select_unidades" => $select_unidades);
			echo Disenio::HTML($url, $parametros);
			break;

		case ($_POST['tablaPpal'] == 'Monitor' || $_POST['tablaPpal'] == 'Impresora' || $_POST['tablaPpal'] == 'Router' || $_POST['tablaPpal'] == 'Switch'):
			$url = array("vista/productos/nueva_marca_y_modelo.php");
			$parametros = array("Producto" => $_POST['tablaPpal']);
			echo Disenio::HTML($url, $parametros);
			break;

		case 'Memoria':
			$url = array("vista/productos/nueva_marca_y_velocidad_memoria.php");
			$select_velocidades = Velocidades::dameSelectVelocidadesPosiblesParaTipo("DDR3");
			$select_tipos = Memoria_desc::dameSelect();
			$parametros = array("Producto" => $_POST['tablaPpal'], "select_velocidades" => $select_velocidades,"select_tipos" => $select_tipos);
			echo Disenio::HTML($url, $parametros);
			break;

		case 'Disco':
			$url = array("vista/productos/nueva_marca.php");
			$parametros = array("Producto" => $_POST['tablaPpal']);
			echo Disenio::HTML($url, $parametros);
			break;
		
		default:
			# code...
			break;
	}

}
else if($_POST['action'] == "borrar_marca"){

	unset($_POST['action']);

	$inst_marcas = new Marcas();

	switch ($_POST['tablaPpal']) {
		
		case ($_POST['tablaPpal'] == 'Computadoras'):
			if(!isset($_POST['cuestion'])){
				$url = array("vista/productos/borrar_marca_y_modelo.php");
				$select_marcas = $inst_marcas->dameSelectABorrar("computadoras");
				$parametros = array("Producto" => $_POST['tablaPpal'],"select_marcas" => $select_marcas);
				echo Disenio::HTML($url, $parametros);
			}
			else if($_POST['cuestion'] == "sel_modelos"){
				$inst_prod = new Computadora_desc();
				echo $inst_prod->dameSelectABorrar($_POST['value']);
			}				
		break;

		case ($_POST['tablaPpal'] == 'Monitores'):
			if(!isset($_POST['cuestion'])){
				$url = array("vista/productos/borrar_marca_y_modelo.php");
				$select_marcas = $inst_marcas->dameSelectABorrar("monitores");
				$parametros = array("Producto" => $_POST['tablaPpal'],"select_marcas" => $select_marcas);
				echo Disenio::HTML($url, $parametros);
			}
			else if($_POST['cuestion'] == "sel_modelos"){
				$inst_prod = new Monitor_desc();
				echo $inst_prod->dameSelectABorrar($_POST['value']);
			}				
		break;

		case ($_POST['tablaPpal'] == 'Impresoras'):
			if(!isset($_POST['cuestion'])){
				$url = array("vista/productos/borrar_marca_y_modelo.php");
				$select_marcas = $inst_marcas->dameSelectABorrar("impresoras");
				$parametros = array("Producto" => $_POST['tablaPpal'],"select_marcas" => $select_marcas);
				echo Disenio::HTML($url, $parametros);
			}
			else if($_POST['cuestion'] == "sel_modelos"){
				$inst_prod = new Impresora_desc();
				echo $inst_prod->dameSelectABorrar($_POST['value']);
			}				
		break;

		case ($_POST['tablaPpal'] == 'Routers'):
			if(!isset($_POST['cuestion'])){
				$url = array("vista/productos/borrar_marca_y_modelo.php");
				$select_marcas = $inst_marcas->dameSelectABorrar("routers");
				$parametros = array("Producto" => $_POST['tablaPpal'],"select_marcas" => $select_marcas);
				echo Disenio::HTML($url, $parametros);
			}
			else if($_POST['cuestion'] == "sel_modelos"){
				$inst_prod = new Router_desc();
				echo $inst_prod->dameSelectABorrar($_POST['value']);
			}				
		break;

		case ($_POST['tablaPpal'] == 'Switchs'):
			if(!isset($_POST['cuestion'])){
				$url = array("vista/productos/borrar_marca_y_modelo.php");
				$select_marcas = $inst_marcas->dameSelectABorrar("switchs");
				$parametros = array("Producto" => $_POST['tablaPpal'],"select_marcas" => $select_marcas);
				echo Disenio::HTML($url, $parametros);
			}
			else if($_POST['cuestion'] == "sel_modelos"){
				$inst_prod = new Switch_desc();
				echo $inst_prod->dameSelectABorrar($_POST['value']);
			}				
		break;

		case ($_POST['tablaPpal'] == 'Memorias'):
			$url = array("vista/productos/borrar_marca.php");
			$select_marcas = $inst_marcas->dameSelectABorrar("memorias");
			$parametros = array("Producto" => $_POST['tablaPpal'],"select_marcas" => $select_marcas);
			echo Disenio::HTML($url, $parametros);	
		break;

		case ($_POST['tablaPpal'] == 'Discos'):
			$url = array("vista/productos/borrar_marca.php");
			$select_marcas = $inst_marcas->dameSelectABorrar("discos");
			$parametros = array("Producto" => $_POST['tablaPpal'],"select_marcas" => $select_marcas);
			echo Disenio::HTML($url, $parametros);
		break;
		
		default:
			# code...
		break;
	}
}

?>
