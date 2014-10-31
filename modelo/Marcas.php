<?php

class Marcas {

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query('select * from system.marcas');
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
		$marca = BDD::getInstance()->query("select nombre from system.marcas where id_marca = '$id' ")->_fetchRow()['nombre'];

		return $marca;
	}

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system.marcas where id_marca = '$id' ")->_fetchRow();
		$fila['marca'] = $fila['nombre'];
		unset($fila['nombre']);
		return $fila;
	}

	public function get_rel_campos() {
		$tabla = BDD::getInstance()->query("select * from system.marcas");
		$array = array();

		while ($fila = $tabla->_fetchRow()) {

			$array[$fila['id_marca']] = $fila['nombre'];

		}
		return $array;
	}
}
?>