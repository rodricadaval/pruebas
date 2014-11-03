<?php

class Memoria {

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
		$tabla = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_memoria = '$id' ")->_fetchRow();
		foreach ($tabla as $campo => $valor) {
			if ($campo == "marca") {
				$tabla['marca'] = Marcas::getNombre($valor);
			} else if ($campo == "capacidad") {
				$tabla['capacidad'] = Capacidad::getNombre($valor);
			} else {
				$tabla[$campo] = $valor;
			}
		}
		return $tabla;
	}
}
?>