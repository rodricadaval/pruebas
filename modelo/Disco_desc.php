<?php

class Disco_desc {

	public static function claseMinus()
	{
		return strtolower(get_class());
	}

	public function listarTodos()
	{

		$inst_table = BDD::getInstance()->query("select * from system.". self::claseMinus());
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

	public function dameDatos($id)
	{
		$fila = BDD::getInstance()->query("select * from system.". self::claseMinus()." where id_disco_desc = '$id' ")->_fetchRow();
		foreach ($fila as $campo => $valor)
		{
			if ($campo == "id_marca")
			{
				$fila['marca'] = Marcas::getNombre($valor);
			}
			else
			{
				$fila[$campo] = $valor;
			}
		}
		return $fila;
	}

	public function dameMarca($id)
	{
		$id_marca = BDD::getInstance()->query("select id_marca from system.". self::claseMinus()." where id_disco_desc = '$id' ")->_fetchRow()['id_marca'];
		return Marcas::getNombre($id_marca);
	}

/*	public function dameSelect($valor = "", $sos = "") {
if (!isset($valor)) {
$table = BDD::getInstance()->query("select id_marca from system." . self::claseMinus() . " where estado = 1");
} else {
$table = BDD::getInstance()->query("select modelo from system." . self::claseMinus() . " where id_marca = '$valor' AND estado = 1");
}
if ($sos != "") {
$html_view = "<select id='select_modelos" . $sos . "' name='modelo'>";
} else {
$html_view = "<select id='select_modelos' name='modelo'>";
}
while ($fila = $table->_fetchRow()) {
$html_view = $html_view . "<option value=" . $fila['modelo'] . ">" . $fila['modelo'] . "</option>";
}
$html_view = $html_view . "</select>";
return $html_view;
}*/

	public function buscar_id_por_marca($id_marca)
	{
		return BDD::getInstance()->query("SELECT id_disco_desc FROM system.disco_desc where id_marca ='$id_marca' ")->_fetchRow()['id_disco_desc'];
	}

	public function agregar_marca($datos)
	{
		$id_marca = $datos['id_marca'];

		if (BDD::getInstance()->query("INSERT INTO system.". self::claseMinus()." (id_marca) VALUES('$id_marca') ")->get_error())
		{
			var_dump(BDD::getInstance());
			return "false";
		}
		else
		{
			return "true";
		}
	}

	public function agregar_nueva_marca($datos)
	{
		$id_marca = $datos['id_marca'];

		if (BDD::getInstance()->query("SELECT * FROM system.". self::claseMinus()." where id_marca = '$id_marca' ")->get_count() > 0)
		{
			return '"estaba"';
		}
		else if (BDD::getInstance()->query("INSERT INTO system.". self::claseMinus()." (id_marca) VALUES('$id_marca') ")->get_error())
		{
			Consola::mostrar(var_dump(BDD::getInstance()));
			return "false";
		}
		else
		{
			return "true";
		}
	}

	public function borrar_marca($datos)
	{
		$id_marca = $datos['marca'];

		if (BDD::getInstance()->query("DELETE FROM system.". self::claseMinus()." where id_marca = '$id_marca'")->get_error())
		{
			var_dump(BDD::getInstance());
			return "false";
		}
		else
		{
			return "true";
		}
	}
}
?>