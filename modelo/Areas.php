<?php
class Areas {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {

		$areas = BDD::getInstance()->query("select * , '<a href=\"#\" class=\"modificar\"id_area=\"' || id_area || '\">MODIFICAR</a>' as m from system." . self::claseMinus());
		$i = 0;
		while ($fila_area = $areas->_fetchRow()) {
			foreach ($fila_area as $campo => $valor) {
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
		echo (json_encode($data));
	}

	public function getByID($id) {
		$area = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_area = '$id' ");
		$elArea = $area->_fetchRow();
		return $elArea;
	}

	public function dameSelect($id = "") {
		$table = BDD::getInstance()->query("select nombre, id_area from system." . self::claseMinus());
		$html_view = "<select id='select_areas' name='area'>";

		while ($fila_area = $table->_fetchRow()) {

			if ($fila_area['id_area'] == $id) {
				$html_view = $html_view . "<option selected='selected' value=" . $fila_area['id_area'] . ">" . $fila_area['nombre'] . "</option>";
				$fila_area = $table->_fetchRow();
			} else if ($fila_area['id_area'] != 1) {
				$html_view = $html_view . "<option value=" . $fila_area['id_area'] . ">" . $fila_area['nombre'] . "</option>";
			}
		}

		$html_view = $html_view . "</select>";
		return $html_view;
	}
	public function getNombre($id) {
		return $inst_table = BDD::getInstance()->query("select nombre from system." . self::claseMinus() . " where id_area = '$id' ")->_fetchRow()['nombre'];
	}

	public function get_rel_campos() {
		$tabla = BDD::getInstance()->query("select * from system." . self::claseMinus());
		$array = array();

		while ($fila = $tabla->_fetchRow()) {

			$array[$fila['id_area']] = $fila['nombre'];

		}
		return $array;
	}
}
?>
