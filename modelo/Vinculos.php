<?php
class Vinculos {

	public function crearVinculoMonitor($datos = "") {

		var_dump($datos);

		if (!isset($datos['id_usuario'])) {
			$datos['id_usuario'] = 1;
			unset($datos['id_usuario']);
		}
		if (!isset($datos['id_cpu'])) {
			$datos['id_cpu'] = 1;
			unset($datos['id_cpu']);
		}
		if (!isset($datos['id_tipo_producto'])) {
			return alert("Rompe todo");
		}

		if (!BDD::getInstance()->query("INSERT INTO system.vinculos (id_usuario,id_sector,id_cpu,id_tipo_producto,id_pk_producto) VALUES ($datos['id_usuario'],$datos['id_deposito'],$datos['id_cpu'],$datos['id_tipo_producto'],1)")->get_error()) {

			$valor_seq_actual = BDD::getInstance()->query("select nextval('system.vinculos_id_vinculo_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual--;
			BDD::getInstance()->query("select setval('system.vinculos_id_vinculo_seq'::regclass,'$valor_seq_actual')")

			$datos['id_vinculo'] = $valor_seq_actual;

			$id_monitor = Monitores::agregar_monitor($datos);
				if(!BDD::getInstance()->("UPDATE system.vinculos SET id_pk_producto='$id_monitor' WHERE id_vinculo='$datos['id_vinculo']' ")
			
	 
	}
}?>