<?php

class Capacidades {

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
		return BDD::getInstance()->query("select capacidad from system." . self::claseMinus() . " where id_capacidad = '$id' ")->_fetchRow()['capacidad'];
	}

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_capacidad = '$id' ")->_fetchRow();

		return $fila;
	}

	public function get_rel_campos() {
		$tabla = BDD::getInstance()->query("select * from system." . self::claseMinus());
		$array = array();

		while ($fila = $tabla->_fetchRow()) {

			$array[$fila['id_capacidad']] = $fila['capacidad'];

		}
		return $array;
	}

		public function dameSelect($valor = "") {


		$array_posta = BDD::getInstance()->query("SELECT id_capacidad, capacidad from system." . self::claseMinus())->_fetchAll();

		$html_view = "<select id='select_capacidades' name='capacidad'>";
		if(count($array_posta) == 0){
			$html_view .= "<option value=''>No hay datos</option>";  
		}

		for ($i = 0; $i < count($array_posta); $i++) {

			if ($array_posta[$i]['capacidad'] != "-") {
				if ($array_posta[$i]['capacidad'] == $valor) {
					$html_view .= "<option selected='selected' value=" . $array_posta[$i]['id_capacidad'] . ">" . $array_posta[$i]['capacidad'] . "</option>";
				} else {
					$html_view .= "<option value=" . $array_posta[$i]['id_capacidad'] . ">" . $array_posta[$i]['capacidad'] . "</option>";
				}
			}
		}

		$html_view = $html_view . "</select>";
		return $html_view;
	}
}
?>