<?php

class Memorias {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query("select * , '<a id=\"modificar_usuario_memoria\" class=\"pointer\"id_memoria=\"' || id_memoria || '\"><i class=\"circular inverted blue small user icon\" title=\"Asignar/Cambiar usuario\"></i></a><a id=\"modificar_cpu_memoria\" class=\"pointer\"$id_memoria=\"' || $id_memoria || '\"><i class=\"circular inverted black small laptop icon\" title=\"Asignar/Cambiar Computadora\"></i></a><a id=\"modificar_memoria\" class=\"pointer\"$id_memoria=\"' || $id_memoria || '\"><i class=\"circular inverted green small sitemap icon\" title=\"Editar sólo Sector\"></i></a> <a id=\"eliminar_memoria\" class=\"pointer\"id_memoria=\"' || id_memoria || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>' as m from system." . self::claseMinus() . " where estado = 1");
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
			'<a id=\"modificar_sector_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"circular inverted black small sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"modificar_cpu_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"circular inverted blue small laptop icon\" title=\"Asignar una Computadora\"></i></a>
			<a id=\"modificar_usuario_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"circular inverted purple small user icon\" title=\"Asignar un Usuario\"></i></a>
			<a id=\"eliminar_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>
			<a id=\"desasignar_todo_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"circular green small minus outline icon\" title=\"Liberar memoria (Quita el usuario y el cpu asignados) \"></i></a>'
			as m from system." . self::claseMinus() . " where estado = 1");

		$todo = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++) {

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor) {

				switch ($campo) {
					case 'id_memoria_desc':
						$arrayAsoc_desc = Memoria_desc::dameDatos($valor);

						foreach ($arrayAsoc_desc as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_vinculo':
						$arrayAsoc_vinculo = Vinculos::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value) {
							if($camp == "nombre_apellido" && $value == "Sin usuario"){
								$value = "-";
							}
								$data[$i][$camp] = $value;			
						}
						break;

					case 'id_capacidad':
						$arrayAsoc_vinculo = Capacidades::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_unidad':
						$arrayAsoc_vinculo = Unidades::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;

					default:
						# code...
					break;
				}
			}
			$data[$i]['capacidad'] .= " " . $data[$i]['unidad'];
		}
		//var_dump($data);

		if ($datos_extra[0] == "json") {
			echo json_encode($data);
		} else {
			return $data;
		}
	}

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where $id_memoria = '$id' ")->_fetchRow();
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

		$id_memoria_desc = Memoria_desc::buscar_id_por_marca_y_tipo($datos['marca'], $datos['tipo_mem']);

		$values = $datos['id_vinculo'] . "," . $datos['capacidad'] . "," . $datos['unidad'] . "," . $id_memoria_desc;

		if (!BDD::getInstance()->query("INSERT INTO system.memorias (id_vinculo,id_capacidad,id_unidad,id_memoria_desc) VALUES ($values)")->get_error()) {
			$valor_seq_actual_memorias = BDD::getInstance()->query("select nextval('system.memorias_id_memoria_seq1'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_memorias--;
			BDD::getInstance()->query("select setval('system.memorias_id_memoria_seq1'::regclass,'$valor_seq_actual_memorias')");
			return $valor_seq_actual_memorias;

		} else {
			var_dump(BDD::getInstance());
			return 0;}
	}

	public function getByID($id) {
		$datos = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_memoria = '$id' ")->_fetchRow();
		foreach ($datos as $key => $value) {
			if ($key == "id_vinculo") {
				$id_usuario = Vinculos::getIdUsuario($value);
				$datos_extra = Usuarios::getByID($id_usuario);
				$id_cpu = Vinculos::getIdCpu($value);
				$datos_extra['nombre_area'] = Areas::getNombre($datos_extra['area']);
			}
			if ($key == "id_capacidad") {
				$datos['capacidad'] = Capacidades::getNombre($value);
			}
			if ($key == "id_unidad"){
				$datos['unidad'] = Unidades::getNombre($value);
			}
		}
		//var_dump($datos);
		return array_merge($datos, $datos_extra);
	}

	public function getCapacidad($id){
		$id_capacidad = BDD::getInstance()->query("select id_capacidad from system.memorias where id_memoria='$id' ")->_fetchRow()['id_capacidad'];
		return Capacidades::getNombre($id_capacidad);
	}

	public function getCapacidadEnMegas($id){
		$fila = BDD::getInstance()->query("select id_capacidad,id_unidad from system.memorias where id_memoria='$id' ")->_fetchRow();
		$id_capacidad = $fila['id_capacidad'];
		$capacidad = Capacidades::getNombre($id_capacidad);
		$id_unidad = $fila['id_unidad'];
		$exponente = BDD::getInstance()->query("select potencia from system.unidades where id_unidad='$id_unidad' ")->_fetchRow()['potencia'];
		$capacidad = $capacidad * pow(1024,$exponente); 
		return $capacidad;
	}

	public function liberar($id){
		$id_vinculo = BDD::getInstance()->query("select id_vinculo from system.memorias where id_memoria='$id' ")->_fetchRow()['id_vinculo'];
		$inst_vinc = new Vinculos();
		echo $inst_vinc->liberar($id_vinculo);
	}
}
?>