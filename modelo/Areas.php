<?php
class Areas {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {

		$areas = BDD::getInstance()->query("select * , '<a href=\"#\" class=\"modificar_area\"id_area=\"' || id_area || '\"><i class=\"circular inverted green small edit icon\"></i></a> <a href=\"#\" class=\"eliminar_area\"id_area=\"' || id_area || '\"><i class=\"circular inverted red small trash icon\"></i></a>' as m from system." . self::claseMinus());
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
		return BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_area = '$id' ")->_fetchRow();
	}

	public function dameSelect($id = "") {
		$table = BDD::getInstance()->query("select nombre, id_area from system." . self::claseMinus());
		if ($id != "") {
			$html_view = "<select disabled id='select_areas' name='area'>";

		} else if ($id == "") {
			$html_view = "<select id='select_areas' name='area'>";
			$html_view .= "<option selected='selected' value=''>Seleccione Area</option>";
		}

		while ($fila_area = $table->_fetchRow()) {

			if ($fila_area['id_area'] == $id) {
				$html_view .= "<option selected='selected' value=" . $fila_area['id_area'] . ">" . $fila_area['nombre'] . "</option>";
			} else if ($fila_area['id_area'] != "") {
				$html_view .= "<option value=" . $fila_area['id_area'] . ">" . $fila_area['nombre'] . "</option>";
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

	public function chequeoExistenciaAreas($area) {
		return BDD::getInstance()->query("select * from system." . self::claseMinus() . " where nombre = '$area' ");
	}

	public function modificarDatos($datos = '') {

		$cadena = '';

		if (isset($datos['id_area'])) {
			$id_area = $datos['id_area'];
			unset($datos['id_area']);
			$nombre = $datos['nombre'];
		}

		$cadena .= "nombre='$nombre'";

		if (!BDD::getInstance()->query("UPDATE system." . self::claseMinus() . " SET $cadena WHERE id_area = '$id_area' ")->get_error()) {
			return 1;
		} else {
			return 0;
		}
	}
}
?>
