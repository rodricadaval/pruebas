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
		if (!isset($datos['cant_veces'])) {
				unset($datos['cant_veces']);
			}
		if($datos['id_tipo_producto'] == 4){
			$datos['id_cpu'] = 1;
		}

		$values = $datos['id_usuario'] . "," . $datos['id_deposito'] . "," . $datos['id_cpu'] . "," . $datos['id_tipo_producto'] . ",1";

		if (!BDD::getInstance()->query("INSERT INTO system.vinculos (id_usuario,id_sector,id_cpu,id_tipo_producto,id_pk_producto) VALUES ($values) ")->get_error()) {

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
					case 'Memoria':
						$id = Memorias::agregar($datos);
						break;
					case 'Disco':
						$id = Discos::agregar($datos);
						break;
					case 'Impresora':
						$id = Impresoras::agregar($datos);
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
			$fila['nombre_apellido'] = Usuarios::getNombreDePila($fila['id_usuario']);
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

	public function getIdCpuDeLaPc($id) {
		return BDD::getInstance()->query("select id_pk_producto from system.vinculos where id_vinculo = '$id' AND id_tipo_producto = 4 ")->_fetchRow()['id_pk_producto'];
	}

	public function mismaArea($id_vinculo, $id_ingresado) {
		if (BDD::getInstance()->query("select id_sector from system.vinculos where id_vinculo = '$id_vinculo' ")->_fetchRow()['id_sector'] == $id_ingresado) {
			return "true";
		}
		return "false";
	}

	public function getByID($id_vinculo){

		$fila = BDD::getInstance()->query("SELECT id_tipo_producto,id_pk_producto FROM system.vinculos WHERE id_vinculo='$id_vinculo' ")->_fetchRow();
		$tipos = Tipo_productos::get_rel_campos();
		$tipo_producto = $tipos[$fila['id_tipo_producto']];
		$id_producto = $fila['id_pk_producto'];

		switch ($tipo_producto) {
			case 'Monitor':
				$datos = Monitores::getByID($id_producto);
				$id_desc = BDD::getInstance()->query("SELECT id_monitor_desc FROM system.monitores WHERE id_monitor='$id_producto' ")->_fetchRow()['id_monitor_desc'];
				$masDatos = Monitor_desc::dameDatos($id_desc);
				$datosFinal = array_merge($datos,$masDatos);
				break;

			case 'Disco':
				$datosFinal = Discos::getByID($fila['id_pk_producto']);
				break;

			case 'Memoria':
				$datos = Memorias::getByID($fila['id_pk_producto']);
				$id_desc = BDD::getInstance()->query("SELECT id_memoria_desc FROM system.memorias WHERE id_memoria='$id_producto' ")->_fetchRow()['id_memoria_desc'];
				$masDatos = Memoria_desc::dameDatos($id_desc);
				$datosFinal = array_merge($datos,$masDatos);
				break;

			case 'Computadora':
				$datos = Computadoras::getByID($fila['id_pk_producto']);
				$id_desc = BDD::getInstance()->query("SELECT id_computadora_desc FROM system.computadoras WHERE id_computadora='$id_producto' ")->_fetchRow()['id_computadora_desc'];
				$masDatos = Computadora_desc::dameDatos($id_desc);
				$datosFinal = array_merge($datos,$masDatos);
				break;

			default:
				# code...
				break;
		}
		
		$datosFinal['producto'] = $tipo_producto;
		return $datosFinal;
		//return var_dump($datosFinal);
	}

	public function cambiarSectorDeAsignados($datos){
		
		$id_cpu = self::getIdCpuDeLaPc($datos['id_vinculo']);
		$extra = "id_sector=" . $datos['id_sector'];

		if (BDD::getInstance()->query("UPDATE system.vinculos SET $extra where id_usuario = 1 AND id_cpu = '$id_cpu' ")->get_error()) {
			var_dump(BDD::getInstance());
			return 0;
		}
		var_dump(BDD::getInstance());
		return 1;
	}

	public function cambiarUsuarioYSectorDeAsignados($datos){
		
		$id_cpu = $datos['id_cpu'];
		$extra = "id_sector=" . $datos['id_sector'].","."id_usuario=".$datos['id_usuario'];

		if (BDD::getInstance()->query("UPDATE system.vinculos SET $extra where id_cpu = '$id_cpu' ")->get_error()) {
			var_dump(BDD::getInstance());
			return 0;
		}
		var_dump(BDD::getInstance());
		return 1;
	}
	
	public function desasignarDeCpu($datos){
		
		$id_cpu = self::getIdCpuDeLaPc($datos['id_vinculo']);
		$extra = "id_cpu=1";

		if (BDD::getInstance()->query("UPDATE system.vinculos SET $extra where id_cpu = '$id_cpu' ")->get_error()) {
			var_dump(BDD::getInstance());
			return 0;
		}
		var_dump(BDD::getInstance());
		return 1;
	}

	public function quitarUsuarioDeAsignados($id_cpu){

		$extra = "id_usuario=1";

		if (BDD::getInstance()->query("UPDATE system.vinculos SET $extra where id_cpu = '$id_cpu' ")->get_error()) {
			var_dump(BDD::getInstance());
			return 0;
		}
		return 1;
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

	public function cambiarCpu($datos){
		$id_vinculo = $datos['id_vinculo'];
		$extra = "id_cpu=".$datos['id_cpu'];
		if (BDD::getInstance()->query("UPDATE system.vinculos SET $extra where id_vinculo = '$id_vinculo' ")->get_error()) {
			var_dump(BDD::getInstance());
			return 0;
		}
		var_dump(BDD::getInstance());
		return 1;
	}

	public function cambiarSector($datos){
		$id_vinculo = $datos['id_vinculo'];
		$extra = "id_sector=".$datos['id_sector'];
		if (BDD::getInstance()->query("UPDATE system.vinculos SET $extra where id_vinculo = '$id_vinculo' ")->get_error()) {
			var_dump(BDD::getInstance());
			return 0;
		}
		var_dump(BDD::getInstance());
		return 1;
	}

	public function cambiarUsuarioYSector($datos){
		$id_vinculo = $datos['id_vinculo'];
		$extra = "id_usuario=".$datos['id_usuario'].","."id_sector=".$datos['id_sector'];
		if (BDD::getInstance()->query("UPDATE system.vinculos SET $extra where id_vinculo = '$id_vinculo' ")->get_error()) {
			var_dump(BDD::getInstance());
			return 0;
		}
		var_dump(BDD::getInstance());
		return 1;
	}

	public function estaLibre($id){
		$resultado = BDD::getInstance()->query("select id_usuario,id_cpu from system.vinculos where id_vinculo = '$id' ")->_fetchRow();
		if($resultado['id_usuario'] == 1 && $resultado['id_cpu'] == 1){
			return 1;
		}
		else{return 0;}
	}

	public function liberar($id){
		if(BDD::getInstance()->query("UPDATE system.vinculos SET id_usuario=1,id_cpu=1 where id_vinculo = '$id' ")->get_error()){
			var_dump(BDD::getInstance());
			echo "false";
		}
		else{echo "true";}
	}
}
?>