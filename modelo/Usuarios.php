<?php

class Usuarios {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarTodos() {
		$armar_tabla = BDD::getInstance()->query("select * , '<a id=\"modificar_usuario\" class=\"pointer\"id_usuario=\"' || id_usuario || '\"><i class=\"circular inverted green small edit icon\"></i></a> <a id=\"eliminar_usuario\" class=\"pointer\"id_usuario=\"' || id_usuario || '\"><i class=\"circular inverted red small trash icon\"></i></a>' as m from system." . self::claseMinus() . " where estado = 1")->_fetchAll();
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
		$fila = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_usuario = '$id' ")->_fetchRow();
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
		unset($datos['id_usuario']);

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

		if (!BDD::getInstance()->query("UPDATE system." . self::claseMinus() . " SET $cadena WHERE id_usuario = '$id' ")->get_error()) {
			return 1;
		} else {
			return 0;
		}
	}

	public function crearUsuario($datos) {

		$cadena_columnas = "";
		$cadena_valores = "";

		$firstTime = true;
		foreach ($datos as $key => $value) {

			if ($value != "") {

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
			} else {unset($datos[$key]);}
		}

		if (!BDD::getInstance()->query("INSERT INTO system." . self::claseMinus() . "($cadena_columnas) VALUES ($cadena_valores) ")->get_error()) {
			return 1;} else {return 0;}
	}

	public function getNombre($id) {
		return $inst_table = BDD::getInstance()->query("select usuario from system." . self::claseMinus() . " where id_usuario = '$id' ")->_fetchRow()['usuario'];
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
		if (!BDD::getInstance()->query("UPDATE system." . self::claseMinus() . " SET estado = 0 WHERE id_usuario = '$id' ")->get_error()) {
			if ($_SESSION['userid'] == $id) {
				session_destroy();
				return 2;
			} else {
				return 1;
			}
		} else {return 0;}
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
}
?>