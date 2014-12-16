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

	public function get_rel_campos() {
		$tabla = BDD::getInstance()->query("select * from system." . self::claseMinus());
		$array = array();

		while ($fila = $tabla->_fetchRow()) {

			$array[$fila['id_marca']] = $fila['nombre'];

		}
		return $array;
	}

	public function dameSelect($sos = "") {

		if(isset($sos)){

			switch ($sos) {
				case 'computadoras':
					$table = BDD::getInstance()->query("select distinct id_marca from system.computadora_desc where estado = 1");
					$add_id = "_computadoras";
					break;
				
				case 'monitores':
					$table = BDD::getInstance()->query("select distinct id_marca from system.monitor_desc where estado = 1");
					$add_id = "_monitores";
					break;

				case 'memorias':
					$table = BDD::getInstance()->query("select distinct id_marca from system.memoria_desc where estado = 1");
					$add_id = "_memorias";
					break;

				default:
					# code...
					break;
			}
		}

		$html_view = "<select id='select_marcas" . $add_id . "' name='marca'>
					  <option value=''>Seleccione Marca</option>";

		$fila = $table->_fetchAll();
		
		foreach ($fila as $array => $campo) {

			$nombre = self::getNombre($campo['id_marca']);
			$html_view .= "<option value=" . $campo['id_marca'] . ">" . $nombre . "</option>";
		 } 

		$html_view .= "</select>";
		return $html_view;
	}
}
?>