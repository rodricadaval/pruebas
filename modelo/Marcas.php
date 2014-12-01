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
		$table = BDD::getInstance()->query("select nombre, id_marca from system." . self::claseMinus() . " where estado = 1");
		$html_view = "<select id='select_marcas" . $sos . "' name='marca'>
					  <option value=''>Seleccione Marca</option>";

		while ($fila = $table->_fetchRow()) {
			$html_view = $html_view . "<option value=" . $fila['id_marca'] . ">" . $fila['nombre'] . "</option>";
		}

		$html_view = $html_view . "</select>";
		return $html_view;
	}
}
?>