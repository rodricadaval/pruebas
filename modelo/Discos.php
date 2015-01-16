<?php

class Discos {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {

		$inst_table = BDD::getInstance()->query("select * , '<a id=\"modificar_usuario_disco\" class=\"pointer\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted blue small user icon\" title=\"Asignar/Cambiar usuario\"></i></a><a id=\"modificar_cpu_disco\" class=\"pointer\"$id_disco=\"' || $id_disco || '\"><i class=\"circular inverted black small laptop icon\" title=\"Asignar/Cambiar Computadora\"></i></a><a id=\"modificar_disco\" class=\"pointer\"$id_disco=\"' || $id_disco || '\"><i class=\"circular inverted green small sitemap icon\" title=\"Editar sólo Sector\"></i></a> <a id=\"eliminar_disco\" class=\"pointer\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>' as m from system." . self::claseMinus() . " where estado = 1");
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

		$data = null;

		$inst_table = BDD::getInstance()->query("select * ,
			'<a id=\"modificar_sector_disco\" class=\"pointer_mon\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted black small sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"modificar_cpu_disco\" class=\"pointer_mon\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted blue small laptop icon\" title=\"Asignar una Computadora\"></i></a>
			<a id=\"modificar_usuario_disco\" class=\"pointer_mon\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted purple small user icon\" title=\"Asignar un Usuario\"></i></a>
			<a id=\"desasignar_todo_disco\" class=\"pointer_mon\"id_disco=\"' || id_disco || '\"><i class=\"circular green small minus outline icon\" title=\"Liberar disco (Quita el usuario y el cpu asignados) \"></i></a>
			<a id=\"eliminar_disco\" class=\"pointer_mon\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>'
			as m from system." . self::claseMinus() . " where estado = 1");

		$todo = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++) {

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor) {

				switch ($campo) {
					case 'id_disco_desc':
						$data[$i]['marca'] = Disco_desc::dameMarca($valor);
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

	public function listarEnStock($datos_extra = "") {

		$data = null;

		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Disco", $tipos);

		$inst_table = BDD::getInstance()->query("select * ,
			'<a id=\"modificar_sector_disco\" class=\"pointer_mon\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted black small sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"modificar_cpu_disco\" class=\"pointer_mon\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted blue small laptop icon\" title=\"Asignar una Computadora\"></i></a>
			<a id=\"modificar_usuario_disco\" class=\"pointer_mon\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted purple small user icon\" title=\"Asignar un Usuario\"></i></a>
			<a id=\"eliminar_disco\" class=\"pointer_mon\"id_disco=\"' || id_disco || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>'
			as m from system." . self::claseMinus() . " where estado = 1 AND id_vinculo IN (select id_vinculo from system.vinculos where id_usuario=1 AND id_cpu=1 AND id_tipo_producto='$id_tipo_producto')");

		$todo = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++) {

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor) {

				switch ($campo) {
					case 'id_disco_desc':
						$data[$i]['marca'] = Disco_desc::dameMarca($valor);
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
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_disco = '$id' ")->_fetchRow();
		foreach ($fila as $campo => $valor) {
			if ($campo == "id_disco_desc") {
				$fila['marca'] = Disco_desc::dameMarcas($valor);
			} else {
				$fila[$campo] = $valor;
			}
		}
		return $fila;
	}

	public function agregar($datos) {

		$id_disco_desc = Disco_desc::buscar_id_por_marca($datos['marca']);

		$values = $datos['id_vinculo'] . "," . $datos['capacidad'] . "," . $datos['unidad'] . "," . $id_disco_desc;

		if (!BDD::getInstance()->query("INSERT INTO system.discos (id_vinculo,id_capacidad,id_unidad,id_disco_desc) VALUES ($values)")->get_error()) {
			$valor_seq_actual_discos = BDD::getInstance()->query("select nextval('system.discos_id_disco_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_discos--;
			BDD::getInstance()->query("select setval('system.discos_id_disco_seq'::regclass,'$valor_seq_actual_discos')");
			return $valor_seq_actual_discos;

		} else {
			var_dump(BDD::getInstance());
			return 0;}
	}

	public function getByID($id) {
		$datos = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_disco = '$id' ")->_fetchRow();
		foreach ($datos as $key => $value) {
			if ($key == "id_vinculo") {
				$id_usuario = Vinculos::getIdUsuario($value);
				$datos_extra = Usuarios::getByID($id_usuario);
				$id_cpu = Vinculos::getIdCpu($value);
				$datos_extra['num_serie_cpu'] = Computadoras::getSerie($id_cpu);
				$datos_extra['nombre_area'] = Areas::getNombre($datos_extra['area']);
			}
			if ($key == "id_capacidad") {
				$datos['capacidad'] = Capacidades::getNombre($value);
			}
			if ($key == "id_unidad"){
				$datos['unidad'] = Unidades::getNombre($value);
			}
			if($key == "id_disco_desc"){
				$datos['marca'] = Disco_desc::dameMarca($value);
			}
		}
		//var_dump($datos);
		return array_merge($datos, $datos_extra);
	}

	public function dameListaDeUsuario($id_usuario){

		$lista_con_datos = null;
		
		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Disco", $tipos);

		$lista = BDD::getInstance()->query("SELECT id_pk_producto FROM system.vinculos where id_tipo_producto='$id_tipo_producto' and id_usuario='$id_usuario' AND estado=1 ")->_fetchAll();
		$i = 0;
		foreach ($lista as $campo) {
			$lista_con_datos[$i] = self::getByID($campo['id_pk_producto']);
			$i++;
		}
		return self::generarListadoDeUsuario($lista_con_datos);	
	}

	public function generarListadoDeUsuario($listado){

		$html_view = "";
		$html_view .= "<fieldset>";
		$html_view .= "<h4>Discos</h4>";
		$html_view .= "<table class='table table-condensed' id='tabla_productos_user'>";
		$html_view .= "<tr>";
		$html_view .= "<th>Marca</th>
					   <th>Capacidad</th>
					   <th>Serie Cpu</th>";
		$html_view .= "</tr>";

		if($listado == null){
			$html_view .= "<tr>";
			$html_view .= "<td colspan='3'>No tiene discos</td>";
			$html_view .= "</tr>";
		}
		else{

			foreach ($listado as $fila => $contenido) {
				$html_view .= "<tr>";

				$html_view .= "<td>".$contenido['marca']."</td>";
				$html_view .= "<td>".$contenido['capacidad']." ".$contenido['unidad']."</td>";
				$html_view .= "<td>".$contenido['num_serie_cpu']."</td>";
		
				$html_view .= "</tr>";
			}
		}
		
		$html_view .= "</table>";
		$html_view .= "</fieldset>";
		return $html_view;
	}

	public function dameListaDeCpu($id_cpu){

		$lista_con_datos = null;

		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Disco", $tipos);

		$lista = BDD::getInstance()->query("SELECT id_pk_producto FROM system.vinculos where id_tipo_producto='$id_tipo_producto' AND id_cpu='$id_cpu' AND estado=1 ")->_fetchAll();
		$i = 0;
		foreach ($lista as $campo) {
			$lista_con_datos[$i] = self::getByID($campo['id_pk_producto']);
			$i++;
		}

		return self::generarListadoDeCpu($lista_con_datos);	
	}

	public function generarListadoDeCpu($listado){

		$html_view = "";
		$html_view .= "<fieldset>";
		$html_view .= "<h4>Discos</h4>";
		$html_view .= "<table class='table table-condensed' id='tabla_productos_cpu'>";
		$html_view .= "<tr>";
		$html_view .= "<th>Marca</th>
					   <th>Capacidad</th>";
		$html_view .= "</tr>";

		if($listado == null){
			$html_view .= "<tr>";
			$html_view .= "<td colspan='2'>No tiene discos</td>";
			$html_view .= "</tr>";
		}
		else{

			foreach ($listado as $fila => $contenido) {
				$html_view .= "<tr>";

				$html_view .= "<td>".$contenido['marca']."</td>";
				$html_view .= "<td>".$contenido['capacidad']." ".$contenido['unidad']."</td>";
		
				$html_view .= "</tr>";
			}
		}

		$html_view .= "</table>";
		$html_view .= "</fieldset>";
		return $html_view;
	}

	public function getCapacidad($id){
		$id_capacidad = BDD::getInstance()->query("select id_capacidad from system.discos where id_disco='$id' ")->_fetchRow()['id_capacidad'];
		return Capacidades::getNombre($id_capacidad);
	}

	public function getCapacidadEnMegas($id){
		$fila = BDD::getInstance()->query("select id_capacidad,id_unidad from system.discos where id_disco='$id' ")->_fetchRow();
		$id_capacidad = $fila['id_capacidad'];
		$capacidad = Capacidades::getNombre($id_capacidad);
		$id_unidad = $fila['id_unidad'];
		$exponente = BDD::getInstance()->query("select potencia from system.unidades where id_unidad='$id_unidad' ")->_fetchRow()['potencia'];
		$capacidad = $capacidad * pow(1024,$exponente); 
		return $capacidad;
	}

	public function liberar($id){
		$id_vinculo = BDD::getInstance()->query("select id_vinculo from system.discos where id_disco='$id' ")->_fetchRow()['id_vinculo'];
		$inst_vinc = new Vinculos();
		echo $inst_vinc->liberar($id_vinculo);
	}

	public function eliminarLogico($datos) {
		$id = $datos['id_disco'];
		$detalle = $datos['detalle_baja'];
		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Disco", $tipos);
		$tabla = "system.discos";
		$campo_pk = "id_disco";

		if(!BDD::getInstance()->query("UPDATE system.discos SET descripcion = '$detalle' where id_disco = '$id'")->get_error()){
			if(!BDD::getInstance()->query("SELECT system.baja_logica_producto('$id','$id_tipo_producto','$tabla','$campo_pk')")->get_error()){
				return 1;
			} else {var_dump(BDD::getInstance()); return 0;}
		} else {var_dump(BDD::getInstance()); return 0;}
	}
}
?>