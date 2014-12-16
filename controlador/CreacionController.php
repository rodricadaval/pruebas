<?php
require '../ini.php';

if (isset($_POST['tipo'])) {
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
				if(Vinculos::crearVinculo($datos, $_POST['tipo']) == "true")
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
		return Vinculos::crearVinculo($datos, $_POST['tipo']);
	}
}

?>
