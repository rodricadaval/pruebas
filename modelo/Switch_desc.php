<?php

class Switch_desc {

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

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_switch_desc = '$id' ")->_fetchRow();
		foreach ($fila as $campo => $valor) {
			if ($campo == "id_marca") {
				$fila['marca'] = Marcas::getNombre($valor);
			} else {
				$fila[$campo] = $valor;
			}
		}
		return $fila;
	}

	public function dameSelect($valor = "", $sos = "") {
		if (!isset($valor)) {
			$table = BDD::getInstance()->query("select modelo from system." . self::claseMinus() . " where estado = 1");
		} else {
			$table = BDD::getInstance()->query("select modelo from system." . self::claseMinus() . " where id_marca = '$valor' AND estado = 1");
		}

		if ($sos != "") {
			$html_view = "<select id='select_modelos" . $sos . "' name='modelo'>";

		} else {
			$html_view = "<select id='select_modelos' name='modelo'>";
		}

		if (BDD::getInstance()->get_count() == 0) {
			$html_view = $html_view . "<option value=''>No hay modelos</option>";
		}

		while ($fila = $table->_fetchRow()) {

			if (preg_match('/\s/', $fila['modelo'])) {
				$modelo = explode(" ", $fila['modelo']);
				$modelo[0] .= "-";
			} else {
				$modelo[0] = $fila['modelo'];
				$modelo[1] = "";
			}

			$html_view = $html_view . "<option value=" . $modelo[0] . $modelo[1] . ">" . $fila['modelo'] . "</option>";
		}

		$html_view = $html_view . "</select>";
		return $html_view;
	}

	public function buscar_id_por_marca_modelo($id_marca, $modelo) {
		return BDD::getInstance()->query("SELECT id_switch_desc FROM system.switch_desc where id_marca ='$id_marca' AND modelo='$modelo' ")->_fetchRow()['id_switch_desc'];
	}

	public function agregar_marca_y_modelo($datos){
		$id_marca = $datos['id_marca'];
		$modelo = $datos['modelo'];

		if(BDD::getInstance()->query("SELECT * FROM system." . self::claseMinus() . " where id_marca = '$id_marca' AND modelo = '$modelo' ")->get_count() > 0){
				return '"estaba"';
		}
		else if(BDD::getInstance()->query("INSERT INTO system." . self::claseMinus() . " (id_marca,modelo) VALUES('$id_marca','$modelo') ")->get_error()){
				var_dump(BDD::getInstance());
				return "false";
		}
		else{
				return "true";
		}
	}
}
?>