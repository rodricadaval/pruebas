<?php

class Permisos {

	public function listarTodos() {
		$inst_table = BDD::getInstance()->query("select *, '<a href=\"#\" class=\"modificar\"tipo_acceso=\"' || tipo_acceso || '\">MODIFICAR</a>' as m from system.permisos");
		$i          = 0;
		while ($fila = $inst_table->_fetchRow()) {
			foreach ($fila as $campo => $valor) {
				$data[$i][$campo] = $valor;
			}
			$i++;
		}

		echo (json_encode($data));
	}

	public function dameSelect($id) {
		$table     = BDD::getInstance()->query("select nombre, tipo_acceso from system.permisos");
		$html_view = "<select id='select_permisos' name='permisos'>";

		while ($fila_permiso = $table->_fetchRow()) {

			if ($fila_permiso['tipo_acceso'] == $id) {
				$html_view = $html_view . "<option selected='selected' value=" . $fila_permiso['tipo_acceso'] . ">" . $fila_permiso['nombre'] . "</option>";
				$fila_area = $table->_fetchRow();
			} else {
				$html_view = $html_view . "<option value=" . $fila_permiso['tipo_acceso'] . ">" . $fila_permiso['nombre'] . "</option>";
			}
		}
		return $html_view;
	}
	public function getNombre($id) {
		return $inst_table = BDD::getInstance()->query("select nombre from system.permisos where tipo_acceso = '$id' ")->_fetchRow()['nombre'];
	}
}
?>