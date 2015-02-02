<?php

class Estado {

	public function listarTodos()
	{

		$inst_table = BDD::getInstance()->query("select * from system.estado")->_fetchAll();
		$i = 0;
		while ($fila = $inst_table->_fetchRow())
		{
			foreach ($fila as $campo => $valor)
			{
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
		echo json_encode($data);
	}

	public function getNombre($id)
	{
		return $inst_table = BDD::getInstance()->query("select nombre from system.estado where id_estado = '$id' ")->_fetchRow()['nombre'];
	}
}
?>