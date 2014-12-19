<?php

class Unidades {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query("select * from system." . self::claseMinus());
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
		return BDD::getInstance()->query("select unidad from system." . self::claseMinus() . " where id_unidad = '$id' ")->_fetchRow()['unidad'];
	}

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_unidad = '$id' ")->_fetchRow();

		return $fila;
	}

	public function get_rel_campos() {
		$tabla = BDD::getInstance()->query("select * from system." . self::claseMinus());
		$array = array();

		while ($fila = $tabla->_fetchRow()) {

			$array[$fila['id_unidad']] = $fila['unidad'];

		}
		return $array;
	}

	public function get_rel_potencia() {
		$tabla = BDD::getInstance()->query("select * from system." . self::claseMinus());
		$array = array();

		while ($fila = $tabla->_fetchRow()) {

			$array[$fila['potencia']] = $fila['unidad'];

		}
		return $array;
	}

	public function dameSelect($valor = "") {


		$array_posta = BDD::getInstance()->query("SELECT id_unidad, unidad from system." . self::claseMinus())->_fetchAll();

		$html_view = "<select id='select_unidades' name='unidad'>";
		if(count($array_posta) == 0){
			$html_view .= "<option value=''>No hay datos</option>";  
		}

		for ($i = 0; $i < count($array_posta); $i++) {

			if ($array_posta[$i]['unidad'] != "-") {
				if ($array_posta[$i]['unidad'] == $valor) {
					$html_view .= "<option selected='selected' value=" . $array_posta[$i]['id_unidad'] . ">" . $array_posta[$i]['unidad'] . "</option>";
				} else {
					$html_view .= "<option value=" . $array_posta[$i]['id_unidad'] . ">" . $array_posta[$i]['unidad'] . "</option>";
				}
			}
		}

		$html_view = $html_view . "</select>";
		return $html_view;
	}
}
?>