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

					/*case 'id_vinculo':
						$arrayAsoc_vinculo = Vinculos::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;*/

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
							if($camp == "unidad"){
								$data[$i]["capacidad"] .= " " . $value;
							} 
						}
						break;

					default:
						# code...
					break;
				}

			}
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
}
?>