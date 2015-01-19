<?php

class Marcas {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query("select *, '<a id=\"modificar_marca\" class=\"pointer\"id_marca=\"' || id_marca || '\"><i class=\"circular inverted green small edit icon\" title=\"Modificar Marca \"></i></a>' as m from system." . self::claseMinus() . " where estado = 1");
		$i = 0;
		while ($fila = $inst_table->_fetchRow()) {
			foreach ($fila as $campo => $valor) {
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
		echo json_encode($data);
	}

	public function getNombre($id) {
		$marca = BDD::getInstance()->query("select nombre from system." . self::claseMinus() . " where id_marca = '$id' ")->_fetchRow()['nombre'];

		return $marca;
	}

	public function chequeoExistenciaMarcas($marca) {
		return BDD::getInstance()->query("select * from system." . self::claseMinus() . " where nombre = '$marca' ");
	}

	public function getByID($id) {
		return BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_marca = '$id' ")->_fetchRow();
	}


	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_marca = '$id' ")->_fetchRow();
		$fila['marca'] = $fila['nombre'];
		unset($fila['nombre']);
		return $fila;
	}

	public function modificarDatos($datos = '') {

		$cadena = '';

		if (isset($datos['id_marca'])) {
			$id_marca = $datos['id_marca'];
			unset($datos['id_marca']);
			$nombre = $datos['nombre'];
		}

		$cadena .= "nombre='$nombre'";

		if (!BDD::getInstance()->query("UPDATE system." . self::claseMinus() . " SET $cadena WHERE id_marca = '$id_marca' ")->get_error()) {
			return 1;
		} else {
			var_dump(BDD::getInstance());
			return 0;
		}
	}

	public function getIdByNombre($nombre) {
		return BDD::getInstance()->query("select id_marca from system." . self::claseMinus() . " where nombre = '$nombre' ")->_fetchRow()['id_marca'];
	}


	public function get_rel_campos() {
		$tabla = BDD::getInstance()->query("select * from system." . self::claseMinus());
		$array = array();

		while ($fila = $tabla->_fetchRow()) {

			$array[$fila['id_marca']] = $fila['nombre'];

		}
		return $array;
	}

	public function agregar($datos){
		if(isset($datos['tipo'])){
			$datos['marca'] = strtoupper($datos['marca']);
			
			switch ($datos['tipo']) {
			 	case 'Monitor':
			 			unset($datos['tipo']);
			 			if(self::existe($datos['marca'])){
			 				$datos['id_marca'] = self::getIdByNombre($datos['marca']);
			 			}
			 			else{
			 				$datos['id_marca'] = self::agregar($datos['marca']);
			 			}
			 			unset($datos['marca']);
			 			echo Monitor_desc::agregar_marca_y_modelo($datos);		
			 		
			 		break;

			 	case 'Impresora':
			 			unset($datos['tipo']);
			 			if(self::existe($datos['marca'])){
			 				$datos['id_marca'] = self::getIdByNombre($datos['marca']);
			 			}
			 			else{
			 				$datos['id_marca'] = self::agregar($datos['marca']);
			 			}
			 			unset($datos['marca']);
			 			echo Impresora_desc::agregar_marca_y_modelo($datos);		
			 		
			 		break;

			 	case 'Computadora':
			 			unset($datos['tipo']);
			 			if(self::existe($datos['marca'])){
			 				$datos['id_marca'] = self::getIdByNombre($datos['marca']);
			 			}
			 			else{
			 				$datos['id_marca'] = self::agregar($datos['marca']);
			 			}
			 			unset($datos['marca']);
			 			$datos['mem_max'] = Capacidades::getNombre($datos['mem_max']);
			 			echo Computadora_desc::agregar_marca_y_modelo($datos);		
			 		
			 		break;

			 	case 'Memoria':
			 			unset($datos['tipo']);
			 			if(self::existe($datos['marca'])){
			 				$datos['id_marca'] = self::getIdByNombre($datos['marca']);
			 			}
			 			else{
			 				$datos['id_marca'] = self::agregar($datos['marca']);
			 			}
			 			unset($datos['marca']);
			 			echo Memoria_desc::agregar_nueva_memoria($datos);		
			 		
			 		break;

			 	case 'Disco':
			 			unset($datos['tipo']);
			 			if(self::existe($datos['marca'])){
			 				$datos['id_marca'] = self::getIdByNombre($datos['marca']);
			 			}
			 			else{
			 				$datos['id_marca'] = self::agregar($datos['marca']);
			 			}
			 			unset($datos['marca']);
			 			echo Disco_desc::agregar_nueva_marca($datos);		
			 		
			 		break;

			 	case 'Router':
			 			unset($datos['tipo']);
			 			if(self::existe($datos['marca'])){
			 				$datos['id_marca'] = self::getIdByNombre($datos['marca']);
			 			}
			 			else{
			 				$datos['id_marca'] = self::agregar($datos['marca']);
			 			}
			 			unset($datos['marca']);
			 			echo Router_desc::agregar_marca_y_modelo($datos);		
			 		
			 		break;

				case 'Switch':
			 			unset($datos['tipo']);
			 			if(self::existe($datos['marca'])){
			 				$datos['id_marca'] = self::getIdByNombre($datos['marca']);
			 			}
			 			else{
			 				$datos['id_marca'] = self::agregar($datos['marca']);
			 			}
			 			unset($datos['marca']);
			 			echo Switch_desc::agregar_marca_y_modelo($datos);		
			 		
			 		break;				

			 	default:
			 		# code...
			 		break;
			 } 
		}
		else{
			if(BDD::getInstance()->query("INSERT INTO system." . self::claseMinus() . " (nombre) VALUES('$datos') ")->get_error()){
				return BDD::getInstance();
			}
			else{
				return BDD::getInstance()->query("select id_marca from system." . self::claseMinus() . " where nombre = '$datos' ")->_fetchRow()['id_marca'];
			}
		}
	}

	public function borrar_de_producto($datos){
		if(isset($datos['tipo'])){
			
			switch ($datos['tipo']) {
			 	case 'Monitores':
			 			unset($datos['tipo']);			 			
			 			echo Monitor_desc::borrar_marca_y_modelo($datos);		
			 		
			 		break;

			 	case 'Impresoras':
			 			unset($datos['tipo']);			 		
			 			echo Impresora_desc::borrar_marca_y_modelo($datos);		
			 		
			 		break;

			 	case 'Computadoras':
			 			unset($datos['tipo']);			 			
			 			echo Computadora_desc::borrar_marca_y_modelo($datos);		
			 		
			 		break;

			 	case 'Memorias':
			 			unset($datos['tipo']);			 			
			 			echo Memoria_desc::borrar_marca($datos);		
			 		
			 		break;

			 	case 'Discos':
			 			unset($datos['tipo']);			 			
			 			echo Disco_desc::borrar_marca($datos);		
			 		
			 		break;

			 	case 'Routers':
			 			unset($datos['tipo']);			 			
			 			echo Router_desc::borrar_marca_y_modelo($datos);		
			 		
			 		break;

				case 'Switchs':
			 			unset($datos['tipo']);			 			
			 			echo Switch_desc::borrar_marca_y_modelo($datos);		
			 		
			 		break;				
			 } 
		}
	}

	public function existe($marca){
		if(BDD::getInstance()->query("select * from system." . self::claseMinus() . " where lower(nombre) = lower('$marca') ")->get_count() > 0){
			return true;
		}
		else{
			return false;
		}
	}

	public function dameSelect($sos = "") {

		if(isset($sos)){

			switch ($sos) {
				case 'computadoras':
					$table = BDD::getInstance()->query("select distinct id_marca from system.computadora_desc where estado = 1")->_fetchAll();
					$add_id = "_computadoras";
					break;
				
				case 'monitores':
					$table = BDD::getInstance()->query("select distinct id_marca from system.monitor_desc where estado = 1")->_fetchAll();
					$add_id = "_monitores";
					break;

				case 'memorias':
					$table = BDD::getInstance()->query("select distinct id_marca from system.memoria_desc where estado = 1")->_fetchAll();
					$add_id = "_memorias";
					break;

				case 'discos':
					
					$table = BDD::getInstance()->query("select distinct id_marca from system.disco_desc where estado = 1")->_fetchAll();
					$add_id = "_discos";
					break;

				case 'impresoras':
					$table = BDD::getInstance()->query("select distinct id_marca from system.impresora_desc where estado = 1")->_fetchAll();
					$add_id = "_impresoras";
					break;

				case 'routers':
					$table = BDD::getInstance()->query("select distinct id_marca from system.router_desc where estado = 1")->_fetchAll();
					$add_id = "_routers";
					break;

				case 'switchs':
					$table = BDD::getInstance()->query("select distinct id_marca from system.switch_desc where estado = 1")->_fetchAll();
					$add_id = "_switchs";
					break;

				default:
					# code...
					break;
			}
		}

		$html_view = "<select id='select_marcas" . $add_id . "' name='marca'>
					  <option value=''>Seleccione Marca</option>";

		$fila = $table;

		foreach ($fila as $array => $campo) {

			$nombre = self::getNombre($campo['id_marca']);
			$html_view .= "<option value=" . $campo['id_marca'] . ">" . $nombre . "</option>";
		 } 

		$html_view .= "</select>";
		return $html_view;
	}

	public function dameSelectABorrar($sos = "") {

		if(isset($sos)){

			switch ($sos) {
				case 'computadoras':
					$table = BDD::getInstance()->query("select distinct id_marca from system.computadora_desc where estado = 1")->_fetchAll();
					break;
				
				case 'monitores':
					$table = BDD::getInstance()->query("select distinct id_marca from system.monitor_desc where estado = 1")->_fetchAll();
					break;

				case 'memorias':
					$table = BDD::getInstance()->query("select distinct id_marca from system.memoria_desc where estado = 1")->_fetchAll();
					break;

				case 'discos':				
					$table = BDD::getInstance()->query("select distinct id_marca from system.disco_desc where estado = 1")->_fetchAll();
					break;

				case 'impresoras':
					$table = BDD::getInstance()->query("select distinct id_marca from system.impresora_desc where estado = 1")->_fetchAll();					
					break;

				case 'routers':
					$table = BDD::getInstance()->query("select distinct id_marca from system.router_desc where estado = 1")->_fetchAll();					
					break;

				case 'switchs':
					$table = BDD::getInstance()->query("select distinct id_marca from system.switch_desc where estado = 1")->_fetchAll();					
					break;

				default:
					# code...
					break;
			}
		}

		$html_view = "<select id='select_marcas_a_borrar' name='marca'>
					  <option value=''>Seleccione Marca</option>";

		$fila = $table;

		foreach ($fila as $array => $campo) {

			$nombre = self::getNombre($campo['id_marca']);
			$html_view .= "<option value=" . $campo['id_marca'] . ">" . $nombre . "</option>";
		 } 

		$html_view .= "</select>";
		return $html_view;
	}
}
?>