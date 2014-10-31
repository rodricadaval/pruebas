<?php

class Monitor {

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query('select * from system.monitores');
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
		return $inst_table = BDD::getInstance()->query("select nombre from system.monitores where id_monitor = '$id' ")->_fetchRow()['id_monitor'];
	}

	public function dameComboBoxCrear() {

		$html_view = "<p>Rellene los campos deseados</p>";

		$table = BDD::getInstance()->query("select * from system.monitores");

		$html_view .= "<select id='select_monitor' name='monitor'>";
		$first = true;
		$inst_marca = new Marcas();
		//$nombres = BDD::getInstance()->query("select nombre from system.marcas");

		while ($fila_monitor = $table->_fetchRow()) {

			//var_dump($fila_monitor);
			//$marca = $inst_marca->dameNombre($fila_monitor['marca']);

			//$un_nombre = $nombres->_fetchRow();

			if ($first) {
				$html_view = $html_view . "<option selected='selected' value=" . $fila_monitor['modelo'] . ">" . $fila_monitor['modelo'] . "</option>";
				$first = false;
			} else {
				$html_view = $html_view . "<option value=" . $fila_monitor['modelo'] . ">" . $fila_monitor['modelo'] . "</option>";
			}
		}

		$html_view .= "</select>";
		return $html_view;
	}

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system.monitores where id_monitor = '$id' ")->_fetchRow();
		foreach ($fila as $campo => $valor) {
			if ($campo == "marca") {
				$fila['marca'] = Marcas::getNombre($valor);
			} else {
				$fila[$campo] = $valor;
			}
		}
		return $fila;
	}
}
?>