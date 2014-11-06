<?php

class Monitores {

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
		return $inst_table = BDD::getInstance()->query("select nombre from system." . self::claseMinus() . " where id_monitor = '$id' ")->_fetchRow()['id_monitor'];
	}

	public function dameComboBoxCrear() {

		$html_view = "<p>Rellene los campos deseados</p>";

		$table = BDD::getInstance()->query("select * from system." . self::claseMinus());

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
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_monitor = '$id' ")->_fetchRow();
		foreach ($fila as $campo => $valor) {
			if ($campo == "marca") {
				$fila['marca'] = Marcas::getNombre($valor);
			} else {
				$fila[$campo] = $valor;
			}
		}
		return $fila;
	}

	public function agregar_monitor($datos) {

		$id_monitor_desc = Monitor_desc::buscar_id_por_marca_modelo($datos['id_marca'], $datos['modelo']);
		$nro_serie = $datos['num_serie'];
		$values = $datos['id_vinculo'] . "," . $id_monitor_desc;

		if (!BDD::getInstance()->query("INSERT INTO system.monitores (num_serie,id_vinculo,id_monitor_desc) VALUES ('$nro_serie',$values)")->get_error()) {
			$valor_seq_actual_monitores = BDD::getInstance()->query("select nextval('system.monitores_id_monitor_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_monitores--;
			if (!BDD::getInstance()->query("select setval('system.monitores_id_monitor_seq'::regclass,'$valor_seq_actual_monitores')")->get_error()) {
				return $valor_seq_actual_monitores;
			} else {var_dump(BDD::getInstance());}
		} else {var_dump(BDD::getInstance());}
	}
}
?>