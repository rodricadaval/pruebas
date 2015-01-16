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

?>
