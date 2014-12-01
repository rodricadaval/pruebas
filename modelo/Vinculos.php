<?php
class Vinculos {

	public function crearVinculo($datos = "", $tipo = "") {

		if (!isset($datos['id_usuario'])) {
			$datos['id_usuario'] = 1;
		}
		if (!isset($datos['id_cpu'])) {
			$datos['id_cpu'] = 1;
		}
		if (!isset($datos['id_deposito'])) {
			$datos['id_deposito'] = 1;
		}
		if (!isset($datos['id_tipo_producto'])) {
			return alert("Rompe todo");
		}

		$values = $datos['id_usuario'] . "," . $datos['id_deposito'] . "," . $datos['id_cpu'] . "," . $datos['id_tipo_producto'] . ",1";

		if (!BDD::getInstance()->query("INSERT INTO system.vinculos (id_usuario,id_sector,id_cpu,id_tipo_producto,id_pk_producto) VALUES ($values) ")->get_error()) {
			var_dump(BDD::getInstance());

			$valor_seq_actual = BDD::getInstance()->query("select nextval('system.vinculos_id_vinculo_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual--;
			$datos['id_vinculo'] = $valor_seq_actual;
			BDD::getInstance()->query("select setval('system.vinculos_id_vinculo_seq'::regclass,$valor_seq_actual)");

			if (isset($tipo)) {
				switch ($tipo) {
					case 'Monitor':
						$id = Monitores::agregar_monitor($datos);
						break;
					case 'Computadora':
						$id = Computadoras::agregar_computadora($datos);
						break;

					default:
						//nada
					break;
				}
			} else {
				Consola::mostrar("No hay tipo especificado.");
				return "false";
			}

			if ($id == 0) {
				BDD::getInstance()->query("DELETE FROM system.vinculos WHERE id_vinculo='$valor_seq_actual' ")->get_error();
				$valor_seq_actual--;
				BDD::getInstance()->query("select setval('system.vinculos_id_vinculo_seq'::regclass,'$valor_seq_actual')");
			} else {

				if (BDD::getInstance()->query("UPDATE system.vinculos SET id_pk_producto=$id WHERE id_vinculo='$valor_seq_actual' ")->get_error()) {
					var_dump(BDD::getInstance());
					return "false";
				} else {
					Consola::mostrar("Se ejecuto correctamente el update del vinculo con el id del producto correspondiente.");
					return "true";
				}

			}
		} else {
			Consola::mostrar("Hubo un error de entrada.");
			var_dump(BDD::getInstance());
			return "false";
		}
	}

	public function dameDatos($id) {
		$fila = BDD::getInstance()->query("select * from system.vinculos where id_vinculo = '$id' ")->_fetchRow();

		if (isset($fila['id_sector'])) {
			$fila['sector'] = Areas::getNombre($fila['id_sector']);
		}

		if (isset($fila['id_usuario'])) {
			$fila['usuario'] = Usuarios::getNombre($fila['id_usuario']);
		}

		if (isset($fila['id_cpu'])) {
			$fila['cpu_serie'] = Computadoras::getSerie($fila['id_cpu']);
		}

		return $fila;
	}

	public function getIdSector($id) {
		return BDD::getInstance()->query("select id_sector from system.vinculos where id_vinculo = '$id' ")->_fetchRow()['id_sector'];
	}
	public function getIdUsuario($id) {
		return BDD::getInstance()->query("select id_usuario from system.vinculos where id_vinculo = '$id' ")->_fetchRow()['id_usuario'];
	}
	public function getIdCpu($id) {
		return BDD::getInstance()->query("select id_cpu from system.vinculos where id_vinculo = '$id' ")->_fetchRow()['id_cpu'];
	}

	public function mismaArea($id_vinculo, $id_ingresado) {
		if (BDD::getInstance()->query("select id_sector from system.vinculos where id_vinculo = '$id_vinculo' ")->_fetchRow()['id_sector'] == $id_ingresado) {
			return "true";
		}
		return "false";
	}

	public function modificarDatos($datos) {

		$id_vinculo = $datos['id_vinculo'];
		$extra = "id_sector=" . $datos['area'] . ", id_usuario=" . $datos['id_usuario'] . ", id_cpu=" . $datos['id_cpu'];
		if (BDD::getInstance()->query("UPDATE system.vinculos SET $extra where id_vinculo = '$id_vinculo' ")->get_error()) {
			var_dump(BDD::getInstance());
			return 0;
		}
		var_dump(BDD::getInstance());
		return 1;
	}
}
?>