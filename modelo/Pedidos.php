<?php

class Pedidos {

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query("select * , '<a href=\"#\" class=\"modificar\"fecha=\"' || fecha || '\"\"&usuario=\"' || usuario || '\"\"&producto=\"' || producto || '\">ELIMINAR</a>' as m from system.pedidos");
		$i          = 0;
		while ($fila = $inst_table->_fetchRow()) {
			foreach ($fila as $campo => $valor) {
				if ($campo == "producto") {
					$valor = Insumos::getNombre($valor);
				}
				if ($campo == "usuario") {
					$valor = Usuarios::getNombre($valor);
				}
				if ($campo == "estado") {
					$valor = Estado::getNombre($valor);
				}
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
		echo json_encode($data);
	}
}
?>