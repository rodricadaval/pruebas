<?php

class Capacidad {

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query('select * from system.capacidad');
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
		$fila = BDD::getInstance()->query("select capacidad from system.capacidad where id_capacidad = '$id' ")->_fetchRow();

		return $fila;
	}

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system.capacidad where id_capacidad = '$id' ")->_fetchRow();

		return $fila;
	}

	public function get_rel_campos() {
		$tabla = BDD::getInstance()->query("select * from system.capacidad");
		$array = array();

		while ($fila = $tabla->_fetchRow()) {

			$array[$fila['id_capacidad']] = $fila['capacidad'];

		}
		return $array;
	}
}
?>