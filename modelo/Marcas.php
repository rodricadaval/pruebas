<?php

class Marcas {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where estado = 1");
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

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_marca = '$id' ")->_fetchRow();
		$fila['marca'] = $fila['nombre'];
		unset($fila['nombre']);
		return $fila;
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

			 	case 'Computadora':
			 			unset($datos['tipo']);
			 			if(self::existe($datos['marca'])){
			 				$datos['id_marca'] = self::getIdByNombre($datos['marca']);
			 			}
			 			else{
			 				$datos['id_marca'] = self::agregar($datos['marca']);
			 			}
			 			unset($datos['marca']);
			 			echo Computadora_desc::agregar_marca_y_modelo($datos);		
			 		
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
					$table = array(array("id_marca" => 1),array("id_marca" => 8),array("id_marca" => 9),array("id_marca" => 10),array("id_marca" => 11),array("id_marca" => 12),array("id_marca" => 3));
					$add_id = "_discos";
					break;

				case 'impresoras':
					$table = BDD::getInstance()->query("select distinct id_marca from system.impresora_desc where estado = 1")->_fetchAll();
					$add_id = "_impresoras";
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
}
?>