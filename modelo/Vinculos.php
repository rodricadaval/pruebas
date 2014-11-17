<?php
class Vinculos {

	public function crearVinculoMonitor($datos = "") {

		if (!isset($datos['id_usuario'])) {
			$datos['id_usuario'] = 1;
		}
		if (!isset($datos['id_cpu'])) {
			$datos['id_cpu'] = 1;
		}
		if (!isset($datos['id_tipo_producto'])) {
			return alert("Rompe todo");
		}

		$values = $datos['id_usuario'] . "," . $datos['id_deposito'] . "," . $datos['id_cpu'] . "," . $datos['id_tipo_producto'] . ",1";

		if (!BDD::getInstance()->query("INSERT INTO system.vinculos (id_usuario,id_sector,id_cpu,id_tipo_producto,id_pk_producto) VALUES ($values) ")->get_error()) {

			$valor_seq_actual = BDD::getInstance()->query("select nextval('system.vinculos_id_vinculo_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual--;
			$datos['id_vinculo'] = $valor_seq_actual;
			BDD::getInstance()->query("select setval('system.vinculos_id_vinculo_seq'::regclass,$valor_seq_actual)");

			$id_monitor = Monitores::agregar_monitor($datos);

			if ($id_monitor == 0) {
				print_r("Hay otro monitor con el mismo numero de Serie");
				BDD::getInstance()->query("DELETE FROM system.vinculos WHERE id_vinculo='$valor_seq_actual' ")->get_error();
				$valor_seq_actual--;
				BDD::getInstance()->query("select setval('system.vinculos_id_vinculo_seq'::regclass,'$valor_seq_actual')");
			} else {

				if (BDD::getInstance()->query("UPDATE system.vinculos SET id_pk_producto=$id_monitor WHERE id_vinculo='$valor_seq_actual' ")->get_error()) {
					var_dump(BDD::getInstance());
					return false;
				} else {
					Consola::mostrar("Se ejecuto correctamente el update del vinculo con el id de monitor correspondiente.");
					//var_dump(BDD::getInstance());
					return true;
				}
			}
		} else {
			Consola::mostrar("Hubo un error de entrada.");
			var_dump(BDD::getInstance());
			return false;
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

	public function modificarDatos($datos) {

		$id_vinculo = $datos['id_vinculo'];
		$set = "id_sector=" . $datos['area'] . ", id_usuario=" . $datos['usuario'] . ", id_cpu=" . $datos['id_computadora'];
		if (BDD::getInstance()->query("UPDATE system.vinculos SET $set where id_vinculo = '$id_vinculo' ")->get_error()) {
			return 0;
		}
		return 1;
	}
}
?>