<?php

class Insumos {

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query('select * from system.insumos');
		$i          = 0;
		while ($fila = $inst_table->_fetchRow()) {
			foreach ($fila as $campo => $valor) {
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
		echo json_encode($data);
	}

	public function getNombre($id) {
		return $inst_table = BDD::getInstance()->query("select nombre from system.insumos where id_insumo = '$id' ")->_fetchRow()['nombre'];
	}
}
?>