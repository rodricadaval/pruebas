<?php

class Usuarios {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {
		$armar_tabla = BDD::getInstance()->query("select * , '<a id=\"modificar_usuario\" class=\"pointer\"id_usuario=\"' || id_usuario || '\"><i title=\"Editar usuario\" class=\"circular inverted green small edit icon\"></i></a> <a id=\"ver_productos\" class=\"pointer\"usuario=\"' || nombre_apellido || '\"><i title=\"Ver productos\" class=\"circular inverted blue small tasks icon\"></i></a> <a id=\"generar_memorandum\" class=\"pointer\"id_usuario=\"' || id_usuario || '\"><i title=\"Generar Memorandum\" class=\"circular inverted orange file pdf outline small icon\"></i></a> <a id=\"eliminar_usuario\" class=\"pointer\"id_usuario=\"' || id_usuario || '\"><i title=\"Eliminar usuario\" class=\"circular inverted red small trash icon\"></i></a>' as m from system." . self::claseMinus() . " where estado = 1 AND id_usuario <> 1")->_fetchAll();
		$i = 0;

		foreach ($armar_tabla as $fila) {

			foreach ($fila as $campo => $valor) {
				if ($campo == "password") {
					$valor = base64_decode(base64_decode(base64_decode($valor)));
				}
				if ($campo == "area") {
					$valor = Areas::getNombre($valor);
				}
				if ($campo == "permisos") {
					$valor = Permisos::getNombre($valor);
				}
				$data[$i][$campo] = $valor;
			}
			$i++;
		}

		echo (json_encode($data));
	}

	public function getById($id) {
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_usuario = '$id' AND estado=1 ")->_fetchRow();
		$fila['password'] = base64_decode(base64_decode(base64_decode($fila['password'])));
		return $fila;
	}

	public function listarPorNombre($nombre) {

		$armar_tabla = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where usuario = '$nombre' ")->_fetchAll();
		$i = 0;

		foreach ($armar_tabla as $fila) {

			foreach ($fila as $campo => $valor) {
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
		echo (json_encode($data));
	}

	public function obtenerUsuarioLogin($usuario, $pass) {
		$pass = base64_encode(base64_encode(base64_encode($pass)));
		return BDD::getInstance()->query("select * from system." . self::claseMinus() . " where usuario = '$usuario' and password = '$pass'");
	}

	public function chequeoExistenciaUsuarios($usuario) {
		return BDD::getInstance()->query("select * from system." . self::claseMinus() . " where usuario = '$usuario' ");
	}

	public function obtener($clave, $id) {
		$clase = $clave . "s";
		$inst = new $clase();
		return $inst->dameNombre($id);
	}

	public function modificarDatos($datos) {

		$cadena = "";

		$id = $datos['id_usuario'];
		$id_area = $datos['area'];
		unset($datos['id_usuario']);

		$con_productos = $datos['con_productos'];
		unset($datos['con_productos']);

		$firstTime = true;
		foreach ($datos as $key => $value) {
			if ($key == "password") {
				$value = base64_encode(base64_encode(base64_encode($value)));
			}
			if ($firstTime) {
				$cadena .= "$key = '$value'";
				$firstTime = false;
			} else {
				$cadena .= ", $key = '$value'";
			}
		}
		
		switch ($con_productos) {
			case 'SI':
					$id_area_vieja = BDD::getInstance()->query("SELECT area from system." . self::claseMinus() . " WHERE id_usuario = '$id' ")->_fetchRow()['area'];				
					if($id_area != $id_area_vieja){
							if(!BDD::getInstance()->query("SELECT system.modificar_area_productos_de_usuario('$id','$id_area')")->get_error()){
								if (!BDD::getInstance()->query("UPDATE system." . self::claseMinus() . " SET $cadena WHERE id_usuario = '$id' ")->get_error()) {
									return 1;
								}
								else{
									var_dump(BDD::getInstance());
									return 0;
								}
							}
							else{	
								var_dump(BDD::getInstance());
								return 0;
								}
					}
					else{
						if (!BDD::getInstance()->query("UPDATE system." . self::claseMinus() . " SET $cadena WHERE id_usuario = '$id' ")->get_error()) {
							return 1;
						}
						else{
							var_dump(BDD::getInstance());
							return 0;
						}
					}
				break;
			
			case 'NO':
					if(!BDD::getInstance()->query("SELECT system.limpiar_productos_de_usuario('$id')")->get_error()){
						if (!BDD::getInstance()->query("UPDATE system." . self::claseMinus() . " SET $cadena WHERE id_usuario = '$id' ")->get_error()) {
							return 1;
						}
						else{
							var_dump(BDD::getInstance());
							return 0;
						}
					}
					else{	
						var_dump(BDD::getInstance());
						return 0;
						}
				break;
		}
}

	public function crearUsuario($datos) {

		$cadena_columnas = "";
		$cadena_valores = "";

		$firstTime = true;

		unset($datos['id_usuario']);

		foreach ($datos as $key => $value) {

			if ($key == "password") {
				$value = base64_encode(base64_encode(base64_encode($value)));
			}
			if ($firstTime) {
				$cadena_valores .= "'$value'";
				$cadena_columnas .= "$key";
				$firstTime = false;
			} else {
				$cadena_columnas .= ",$key";
				$cadena_valores .= ",'$value'";
			}
		}

		if (!BDD::getInstance()->query("INSERT INTO system." . self::claseMinus() . "($cadena_columnas) VALUES ($cadena_valores) ")->get_error()) {
			return 1;} else {var_dump(BDD::getInstance());return 0;}
	}

	public function getNombre($id) {
		return $inst_table = BDD::getInstance()->query("select usuario from system." . self::claseMinus() . " where id_usuario = '$id' ")->_fetchRow()['usuario'];
	}

	public function getNombreDePila($id) {
		return $inst_table = BDD::getInstance()->query("select nombre_apellido from system." . self::claseMinus() . " where id_usuario = '$id' ")->_fetchRow()['nombre_apellido'];
	}

	public function getIdByNombre($nombre) {
		return BDD::getInstance()->query("select id_usuario from system.usuarios where nombre_apellido= '$nombre' ")->_fetchRow()['id_usuario'];
	}

	public function getCampos() {

		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus())->_fetchRow();

		foreach ($fila as $key => $value) {
			$datos[$key] = "";
		}
		return $datos;
	}

	public function dameUsuarios() {
		return BDD::getInstance()->query("select usuario from system." . self::claseMinus() . " where estado = 1")->_fetchAll();
	}

	public function dameNombres() {
		return BDD::getInstance()->query("select nombre_apellido from system." . self::claseMinus() . " where estado = 1")->_fetchAll();
	}

	public function eliminarUsuario($id) {
		if(!BDD::getInstance()->query("SELECT system.limpiar_productos_de_usuario('$id')")->get_error()){
				if (!BDD::getInstance()->query("UPDATE system." . self::claseMinus() . " SET estado = 0 WHERE id_usuario = '$id' ")->get_error()) {
					if ($_SESSION['userid'] == $id) {
						session_destroy();
						return 2;
					} else {
						return 1;
					}
				} else {
						var_dump(BDD::getInstance());
						return 0;
				}
		}
		else{
			var_dump(BDD::getInstance());
			return 0;
		}
	}

	public function eliminarRealUsuario($id) {
		
		if(!BDD::getInstance()->query("SELECT system.limpiar_productos_de_usuario('$id')")->get_error()){
				if (!BDD::getInstance()->query("DELETE FROM system." . self::claseMinus() . " WHERE id_usuario = '$id' ")->get_error()) {
					if ($_SESSION['userid'] == $id) {
						session_destroy();
						return 2;
					} else {
						return 1;
					}
				} else {
						var_dump(BDD::getInstance());
						return 0;
				}
		}
		else{
			var_dump(BDD::getInstance());
			return 0;
		}
	}

	public function dame_id_area($id) {
		return BDD::getInstance()->query("select area FROM system." . self::claseMinus() . " WHERE id_usuario = '$id' ")->_fetchRow()['area'];
	}

	public function dameSelect($id = "", $sos = "") {
		$table = BDD::getInstance()->query("select usuario, id_usuario, nombre_apellido from system." . self::claseMinus() . " where id_usuario <> 1 order by nombre_apellido,usuario asc");
		$html_view = "<select id=" . 'select_usuarios' . '_' . $sos . " name='usuario'>";
		$html_view .= "<option selected='selected' value=''>Seleccionar</option>";
		$html_view .= "<option value=1>Ninguno</option>";

		while ($fila_usuario = $table->_fetchRow()) {

			if ($fila_usuario['id_usuario'] == $id) {
				$html_view .= "<option selected='selected' value=" . $fila_usuario['id_usuario'] . ">" . $fila_usuario['nombre_apellido'] . "  |  " . $fila_usuario['usuario'] . "</option>";
			} else if ($fila_usuario['id_usuario'] != "") {
				$html_view .= "<option value=" . $fila_usuario['id_usuario'] . ">" . $fila_usuario['nombre_apellido'] . "  |  " . $fila_usuario['usuario'] . "</option>";
			}
		}

		$html_view = $html_view . "</select>";
		return $html_view;
	}

	public function dameListadoMemoDeUsuario($id){
		
		$lista = BDD::getInstance()->query("SELECT id_vinculo FROM system.vinculos WHERE id_usuario='$id' ")->_fetchAll();
		$i = 0;
		foreach ($lista as $campo) {
			$lista_con_datos[$i] = Vinculos::getByID($campo['id_vinculo']);
			$i++;
		}
		return self::generarListadoMemorandum($lista_con_datos);	
	}

	public function generarListadoMemorandum($listado){

		$html_view = "";
		$html_view .= "<table class='table table-condensed' id='tabla_listado_memorandum_user'>";
		$html_view .= "<tr>";
		$html_view .= "<th></th>";
		$html_view .= "<th>Producto</th>
					   <th>Detalle</th>";
		$html_view .= "</tr>";

		if(count($listado) == 0 ){
			$html_view .= "<tr>";
			$html_view .= "<td colspan='2'>No tiene productos</td>";
			$html_view .= "</tr>";
		}

		foreach ($listado as $fila => $contenido) {
			$html_view .= "<tr>";
			$vinculo = $contenido['id_vinculo'];
			
			$html_view .= "<td><input type='checkbox' id='productos_seleccionados' value='$vinculo'></td>";
			$html_view .= "<td>".$contenido['producto']."</td>";
			switch ($contenido['producto']) {
				case "Computadora":
					$tipos = Tipos_Computadoras::get_rel_campos();
					$clase = $contenido['clase'];
					$tipo_producto = array_search($clase, $tipos);

					$html_view .= "<td>".$tipo_producto.": ".$contenido['marca'].",".$contenido['modelo'].",".$contenido['num_serie']."</td>";
					break;

				case 'Monitor':
					$html_view .= "<td>".$contenido['marca'].",".$contenido['modelo'].",".$contenido['num_serie']."</td>";
					break;
				
				case 'Memoria':
					$html_view .= "<td>".$contenido['marca'].",".$contenido['capacidad']." ".$contenido['unidad'].",".$contenido['tipo']." ".$contenido['velocidad']."</td>";
					break;

				case 'Disco':
						$html_view .= "<td>".$contenido['marca']." ".$contenido['capacidad'].$contenido['unidad']."</td>";	
						break;	
				default:
					$html_view .= "<td>".$contenido['marca']."</td>";
					break;
			}
	
			$html_view .= "</tr>";
		}

		$html_view .= "</table>";
		return $html_view;

	}

	/*public function generarListadoDeUsuario($listado){

		$html_view = "";
		$html_view .= "<fieldset>";
		$html_view .= "<h4>Productos</h4>";
		$html_view .= "<table class='table table-condensed' id='tabla_listado_memorandum_user'>";
		$html_view .= "<tr>";
		$html_view .= "<th>Producto</th>
					   <th>Marca</th>";
					   <th>Modelo</th>
					   <th>Pulgadas</th>
					   <th>Serie Cpu</th>";
		$html_view .= "</tr>";

		if(count($listado) == 0 ){
			$html_view .= "<tr>";
			$html_view .= "<td colspan='5'>No tiene productos</td>";
			$html_view .= "</tr>";
		}

		foreach ($listado as $fila => $contenido) {
			$html_view .= "<tr>";

			$html_view .= "<td>".$contenido['num_serie']."</td>";

			$datos_desc = Monitor_desc::dameDatos($contenido['id_monitor_desc']);
			if($datos_desc['pulgadas'] == ""){$datos_desc['pulgadas'] = "-";}
			
			$html_view .= "<td>".$datos_desc['marca']."</td>";
			$html_view .= "<td>".$datos_desc['modelo']."</td>";
			$html_view .= "<td>".$datos_desc['pulgadas']."</td>";
			$html_view .= "<td>".$contenido['num_serie_cpu']."</td>";
	
			$html_view .= "</tr>";
		}

		$html_view .= "</table>";
		$html_view .= "</fieldset>";
		return $html_view;

	}*/
}
?>