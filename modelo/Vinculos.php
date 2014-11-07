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
					var_dump(BDD::getInstance());
					return true;
				}
			}
		} else {
			Consola::mostrar("Hubo un error de entrada.");
			var_dump(BDD::getInstance());
			return false;
		}
	}
}
?>