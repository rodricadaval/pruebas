<?php

class Impresoras {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query("select * , '<a id=\"modificar_usuario_impresora\" class=\"pointer\"id_impresora=\"' || id_impresora || '\"><i class=\"circular inverted blue small user icon\" title=\"Asignar/Cambiar usuario\"></i></a><a id=\"modificar_cpu_impresora\" class=\"pointer\"id_impresora=\"' || id_impresora || '\"><i class=\"circular inverted black small laptop icon\" title=\"Asignar/Cambiar Computadora\"></i></a><a id=\"modificar_impresora\" class=\"pointer\"id_impresora=\"' || id_impresora || '\"><i class=\"circular inverted green small sitemap icon\" title=\"Editar sólo Sector\"></i></a> <a id=\"eliminar_impresora\" class=\"pointer\"id_impresora=\"' || id_impresora || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>' as m from system." . self::claseMinus() . " where estado = 1");
		$i = 0;
		while ($fila = $inst_table->_fetchRow()) {
			foreach ($fila as $campo => $valor) {
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
		echo json_encode($data);
	}

	public function listarCorrecto($datos_extra = "") {

		$inst_table = BDD::getInstance()->query("select * ,
			'<a id=\"modificar_sector_impresora\" class=\"pointer_mon\"id_impresora=\"' || id_impresora || '\"><i class=\"circular inverted black small sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"agregar_descripcion_impresora\" class=\"pointer_cpu\"id_impresora=\"' || id_impresora || '\">
			<i class=\"circular inverted blue small book icon\" title=\"Ver o editar descripcion\"></i></a>
			<a id=\"eliminar_impresora\" class=\"pointer_mon\"id_impresora=\"' || id_impresora || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>'
			as m from system." . self::claseMinus() . " where estado = 1");

		$todo = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++) {

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor) {

				switch ($campo) {
					case 'id_impresora_desc':
						$arrayAsoc_desc = Impresora_desc::dameDatos($valor);

						foreach ($arrayAsoc_desc as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_vinculo':
						$arrayAsoc_vinculo = Vinculos::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;

					default:
						# code...
					break;
				}

			}
		}

		if ($datos_extra[0] == "json") {
			echo json_encode($data);
		} else {
			return $data;
		}
	}

	public function listarEnStock($datos_extra = "") {

		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Impresora", $tipos);

		$inst_table = BDD::getInstance()->query("select * ,
			'<a id=\"modificar_sector_impresora\" class=\"pointer_mon\"id_impresora=\"' || id_impresora || '\"><i class=\"circular inverted black small sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"agregar_descripcion_impresora\" class=\"pointer_cpu\"id_impresora=\"' || id_impresora || '\">
			<i class=\"circular inverted blue small book icon\" title=\"Ver o editar descripcion\"></i></a>
			<a id=\"eliminar_impresora\" class=\"pointer_mon\"id_impresora=\"' || id_impresora || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>'
			as m from system." . self::claseMinus() . " where estado = 1 AND id_vinculo IN (select id_vinculo from system.vinculos where id_sector=1 AND id_tipo_producto='$id_tipo_producto')");

		$todo = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++) {

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor) {

				switch ($campo) {
					case 'id_impresora_desc':
						$arrayAsoc_desc = Impresora_desc::dameDatos($valor);

						foreach ($arrayAsoc_desc as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_vinculo':
						$arrayAsoc_vinculo = Vinculos::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;

					default:
						# code...
					break;
				}

			}
		}

		if ($datos_extra[0] == "json") {
			echo json_encode($data);
		} else {
			return $data;
		}
	}

	public function dameComboBoxCrear() {

		$html_view = "<p>Rellene los campos deseados</p>";

		$table = BDD::getInstance()->query("select * from system." . self::claseMinus());

		$html_view .= "<select id='select_impresora' name='impresora'>";
		$first = true;
		$inst_marca = new Marcas();

		while ($fila_impresora = $table->_fetchRow()) {

			if ($first) {
				$html_view = $html_view . "<option selected='selected' value=" . $fila_impresora['modelo'] . ">" . $fila_impresora['modelo'] . "</option>";
				$first = false;
			} else {
				$html_view = $html_view . "<option value=" . $fila_impresora['modelo'] . ">" . $fila_impresora['modelo'] . "</option>";
			}
		}

		$html_view .= "</select>";
		return $html_view;
	}

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_impresora = '$id' ")->_fetchRow();
		foreach ($fila as $campo => $valor) {
			if ($campo == "marca") {
				$fila['marca'] = Marcas::getNombre($valor);
			} else {
				$fila[$campo] = $valor;
			}
		}
		return $fila;
	}

	public function agregar($datos) {

		$id_impresora_desc = Impresora_desc::buscar_id_por_marca_modelo($datos['marca'], $datos['modelo']);
		$nro_serie = $datos['num_serie'];
		$ip = $datos['ip'];
		$values = $datos['id_vinculo'] . "," . $id_impresora_desc;

		if (!BDD::getInstance()->query("INSERT INTO system.impresoras (num_serie,ip,id_vinculo,id_impresora_desc) VALUES ('$nro_serie','$ip',$values)")->get_error()) {
			$valor_seq_actual_impresoras = BDD::getInstance()->query("select nextval('system.impresoras_id_impresora_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_impresoras--;
			BDD::getInstance()->query("select setval('system.impresoras_id_impresora_seq'::regclass,'$valor_seq_actual_impresoras')");
			return $valor_seq_actual_impresoras;

		} else {
			var_dump(BDD::getInstance());
			return 0;}
	}

	public function no_existe($nro) {

		if (BDD::getInstance()->query("select num_serie from system." . self::claseMinus() . " where num_serie = '$nro' ")->get_count()) {
			return 0;
		} else {
			return 1;
		}
	}

	public function getByID($id) {
		$datos = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_impresora = '$id' ")->_fetchRow();
		foreach ($datos as $key => $value) {
			if ($key == "id_vinculo") {
				$id_usuario = Vinculos::getIdUsuario($value);
				$datos_usuario = Usuarios::getByID($id_usuario);
				$id_cpu = Vinculos::getIdCpu($value);
				$datos_usuario['num_serie_cpu'] = Computadoras::getSerie($id_cpu);
				$datos_usuario['nombre_area'] = Areas::getNombre($datos_usuario['area']);
			}
		}
		return array_merge($datos, $datos_usuario);

	}

	public function modificarImpresora($datos) {
		return Vinculos::modificarDatos($datos);
	}

	public function eliminarImpresora($id) {
		if (!BDD::getInstance()->query("DELETE FROM system.impresoras where id_impresora='$id' ")->get_error()) {
			return 1;
		} else {return 0;}
	}

	public function eliminarLogico($datos) {
		$id = $datos['id_impresora'];
		$detalle = $datos['detalle_baja'];
		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Impresora", $tipos);
		$tabla = "system.impresoras";
		$campo_pk = "id_impresora";

		if(!BDD::getInstance()->query("UPDATE system.impresoras SET descripcion = '$detalle' where id_impresora = '$id'")->get_error()){
			if(!BDD::getInstance()->query("SELECT system.baja_logica_producto('$id','$id_tipo_producto','$tabla','$campo_pk')")->get_error()){
				return 1;
			} else {var_dump(BDD::getInstance()); return 0;}
		} else {var_dump(BDD::getInstance()); return 0;}
	}

	public function agregarDescripcion($datos) {
		$id = $datos['id_impresora'];
		$detalle = $datos['descripcion'];

		if(!BDD::getInstance()->query("UPDATE system.impresoras SET descripcion = '$detalle' where id_impresora = '$id'")->get_error()){
			return 1;
		} else {var_dump(BDD::getInstance()); return 0;}
	}
}
?>