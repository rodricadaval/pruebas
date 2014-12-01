<?php

class Monitores {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query("select * , '<a id=\"modificar_usuario_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted blue small user icon\" title=\"Asignar/Cambiar usuario\"></i></a><a id=\"modificar_cpu_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted black small laptop icon\" title=\"Asignar/Cambiar Computadora\"></i></a><a id=\"modificar_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted green small sitemap icon\" title=\"Editar sólo Sector\"></i></a> <a id=\"eliminar_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>' as m from system." . self::claseMinus() . " where estado = 1");
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
			'<a id=\"modificar_usuario_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted green small edit icon\" title=\"Asignar a Usuario,Cpu o cambiar Sector \"></i></a>
			<a id=\"modificar_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted black small sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"modificar_cpu_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted blue small laptop icon\" title=\"Asignar por Computadora\"></i></a>
			<a id=\"modificar_usuario_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted purple small user icon\" title=\"Asignar por Usuario\"></i></a>
			<a id=\"eliminar_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>
			<a id=\"desasignar_todo_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"circular green small minus outline icon\" title=\"Cambiar Sector \"></i></a>'
			as m from system." . self::claseMinus() . " where estado = 1");

		$todo = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++) {

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor) {

				switch ($campo) {
					case 'id_monitor_desc':
						$arrayAsoc_desc = Monitor_desc::dameDatos($valor);

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

	public function getNombre($id) {
		return BDD::getInstance()->query("select nombre from system." . self::claseMinus() . " where id_monitor = '$id' ")->_fetchRow()['id_monitor'];
	}

	public function dameComboBoxCrear() {

		$html_view = "<p>Rellene los campos deseados</p>";

		$table = BDD::getInstance()->query("select * from system." . self::claseMinus());

		$html_view .= "<select id='select_monitor' name='monitor'>";
		$first = true;
		$inst_marca = new Marcas();

		while ($fila_monitor = $table->_fetchRow()) {

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

		$id_monitor_desc = Monitor_desc::buscar_id_por_marca_modelo($datos['marca'], $datos['modelo']);
		$nro_serie = $datos['num_serie'];
		$values = $datos['id_vinculo'] . "," . $id_monitor_desc;

		if (!BDD::getInstance()->query("INSERT INTO system.monitores (num_serie,id_vinculo,id_monitor_desc) VALUES ('$nro_serie',$values)")->get_error()) {
			$valor_seq_actual_monitores = BDD::getInstance()->query("select nextval('system.monitores_id_monitor_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_monitores--;
			BDD::getInstance()->query("select setval('system.monitores_id_monitor_seq'::regclass,'$valor_seq_actual_monitores')");
			return $valor_seq_actual_monitores;

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
		$datos = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_monitor = '$id' ")->_fetchRow();
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

	public function modificarMonitor($datos) {
		return Vinculos::modificarDatos($datos);
	}

	public function eliminarMonitor($id) {
		if (!BDD::getInstance()->query("DELETE FROM system.monitores where id_monitor='$id' ")->get_error()) {
			return 1;
		} else {return 0;}
	}

	public function eliminarMonitorLogico($id) {
		if (!BDD::getInstance()->query("UPDATE system.monitores SET estado=0 where id_monitor='$id' ")->get_error()) {
			return 1;
		} else {return 0;}
	}
}
?>