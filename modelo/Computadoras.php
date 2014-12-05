<?php

class Computadoras {

	public static function claseMinus() {
		return strtolower(get_class());
	}

	public function listarCorrecto($datos_extra = "") {

		$inst_table = BDD::getInstance()->query("
			select * , 
			'<a id=\"modificar_sector_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"circular inverted black small sitemap icon\" title=\"Cambiar Sector \"></i>
			</a>
			<a id=\"modificar_tipo_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"circular inverted green small edit icon\" title=\"Cambiar Tipo\"></i>
			</a>
			<a id=\"modificar_usuario_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"circular inverted purple small user icon\" title=\"Asignar un Usuario\"></i>
			</a>
			<a id=\"eliminar_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\"><i class=\"circular inverted red small trash icon\"></i></a>' as m 
			from system." . self::claseMinus() . " 
			where estado = 1 and id_computadora <> 1");

		$todo = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++) {

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor) {

				switch ($campo) {
					case 'id_computadora_desc':
						$arrayAsoc_desc = Computadora_desc::dameDatos($valor);

						foreach ($arrayAsoc_desc as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_vinculo':
						$arrayAsoc_vinculo = Vinculos::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value) {
							$data[$i][$camp] = $value;
						}
						break;

					default:
						# code...
					break;
				}

			}
		}

		if ($datos_extra[0] == "json") {
			echo json_encode($data);
		} else {
			return $data;
		}
	}
/*
public function dameDatos($id) {
$tabla = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_cpu = '$id' ")->_fetchRow();
foreach ($tabla as $campo => $valor) {
if ($campo == "id_vinculo") {
//$tabla['marca'] = Marcas::getnum_serie($valor);
} else if ($campo == "id_computadora_desc") {
//Computadora_desc::
} else {
$tabla[$campo] = $valor;
}
}
return $tabla;
}
 */

	public function agregar_computadora($datos) {

		$id_computadora_desc = Computadora_desc::buscar_id_por_marca_modelo($datos['marca'], $datos['modelo']);
		$nro_serie = $datos['num_serie'];
		$clase = $datos['clase'];
		$values = $datos['id_vinculo'] . "," . $id_computadora_desc;

		if (!BDD::getInstance()->query("INSERT INTO system.computadoras (num_serie,clase,id_vinculo,id_computadora_desc) VALUES ('$nro_serie','$clase',$values)")->get_error()) {
			$valor_seq_actual_computadoras = BDD::getInstance()->query("select nextval('system.computadoras_id_computadora_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_computadoras--;
			BDD::getInstance()->query("select setval('system.computadoras_id_computadora_seq'::regclass,'$valor_seq_actual_computadoras')");
			return $valor_seq_actual_computadoras;

		} else {
			var_dump(BDD::getInstance());
			return 0;}
	}

	public function getSerie($id) {
		return BDD::getInstance()->query("select num_serie from system.computadoras where id_computadora='$id'")->_fetchRow()['num_serie'];
	}

	public function getIdVinculoBySerie($serie) {
		return BDD::getInstance()->query("select id_vinculo from system.computadoras where num_serie = '$serie' ")->_fetchRow()['id_vinculo'];
	}

	public function cambiarTipo($datos){
		$clase = $datos['clase'];
		$id = $datos['id_computadora'];
		if(BDD::getInstance()->query("UPDATE system.computadoras SET clase='$clase' where id_computadora='$id'")->get_error()){
			return "false";
		}
		else{return "true";}
	}

	public function getIdBySerie($serie) {
		return BDD::getInstance()->query("select id_computadora from system.computadoras where num_serie = '$serie' ")->_fetchRow()['id_computadora'];
	}

	public function dameSelect_clase($clase = "", $sos = "") {

		return Tipos_Computadoras::dameSelect_clase($clase, $sos);
	}

	public function dameSelect($id = "", $sos = "") {
		$table = BDD::getInstance()->query("select num_serie, id_computadora from system." . self::claseMinus() . " where estado = 1");
		if ($id != "") {
			$html_view = "<select id=" . 'select_computadoras' . '_' . $sos . " name='id_computadora'>";

		} else if ($id == "") {
			$html_view = "<select id=" . 'select_computadoras' . '_' . $sos . " name='id_computadora'>";
			$html_view .= "<option selected='selected' value=''>Seleccione computadora</option>";
		}

		while ($fila_computadora = $table->_fetchRow()) {

			if ($fila_computadora['id_computadora'] == $id) {
				$html_view .= "<option selected='selected' value=" . $fila_computadora['id_computadora'] . ">" . $fila_computadora['num_serie'] . "</option>";
			} else if ($fila_computadora['id_computadora'] != "") {
				$html_view .= "<option value=" . $fila_computadora['id_computadora'] . ">" . $fila_computadora['num_serie'] . "</option>";
			}

		}

		$html_view = $html_view . "</select>";
		return $html_view;
	}

	public function dameSelectDeUsuario($id = "", $sos = "") {

		$nuevo = true;
		$consulta = "";

		if($sos == "dialog_monitor_mod_cpu_sin_usr"){
			$id_sector = BDD::getInstance()->query("select id_sector from system.vinculos where id_vinculo = '$id' ")->_fetchRow()['id_sector'];
			$tableVinc = BDD::getInstance()->query("select distinct id_pk_producto from system.vinculos where id_usuario = 1 AND id_sector = '$id_sector' AND id_tipo_producto = 4");
			$nuevo = false;
			$consulta .= 1;
		}
		else{
			$tableVinc = BDD::getInstance()->query("select distinct id_pk_producto from system.vinculos where id_usuario = '$id' AND id_tipo_producto = 4 ");
		}

		$html_view = "<select id='select_computadoras_" . $sos . "' name='id_computadora'>";

		if(($id == "" || $id == 1 || $tableVinc->get_count() == 0) && $sos != "dialog_monitor_mod_cpu_sin_usr"){
			$html_view .= "<option value='1'>Sin Cpu</option>";
		}
		else{

			while ($fila_vinc = $tableVinc->_fetchRow()) {

				$id_cpu = $fila_vinc['id_pk_producto'];

				if($nuevo){
					$consulta .= $id_cpu;
					$nuevo = false;
				}
				else{
					$consulta .= "," . $id_cpu;
				}
			}
	
			$table = BDD::getInstance()->query("select num_serie,id_computadora from system.computadoras where id_computadora IN ($consulta)");

		    while ($fila_computadora = $table->_fetchRow()) {

					$html_view .= "<option value='" . $fila_computadora['id_computadora'] . "'>" . $fila_computadora['num_serie'] . "</option>";
				}
		}
		$html_view = $html_view . "</select>";

		return $html_view;
	}

	public function modificar($datos) {
		$datos['id_usuario'] = Usuarios::getIdByNombre($datos['nombre_usuario']);
		unset($datos['nombre_usuario']);
		$clase = $datos['clase'];
		$id = $datos['id_cpu'];
		BDD::getInstance()->query("UPDATE system.computadoras SET clase='$clase' where id_computadora='$id'");
		return Vinculos::modificarDatos($datos);
	}

	public function modificarConAsignados($datos) {
		$inst_vinc = new Vinculos();
		if($inst_vinc->cambiarSector($datos)){
				if($inst_vinc->cambiarSectorDeAsignados($datos)){
					return "true";
				}
				else{
					return "false";
				}
		} 
		else{
			return "false";
		}
	}
	
	public function modificarSinAsignados($datos) {
		$inst_vinc = new Vinculos();
		if($inst_vinc->cambiarSector($datos)){
				if($inst_vinc->desasignarDeCpu($datos)){
					return "true";
				}
				else{
					return "false";
				}
		} 
		else{
			return "false";
		}
	}

	public function eliminarLogico($id) {
		if (!BDD::getInstance()->query("UPDATE system.computadoras SET estado=0 where id_computadora='$id' ")->get_error()) {
			return 1;
		} else {return 0;}
	}

	public function eliminar($id) {
		if (!BDD::getInstance()->query("DELETE FROM system.computadoras where id_computadora='$id' ")->get_error()) {
			return 1;
		} else {return 0;}
	}

	public function getByID($id) {
		$datos_compu = BDD::getInstance()->query("select * from system." . self::claseMinus() . " where id_computadora = '$id' ")->_fetchRow();

		$id_usuario = Vinculos::getIdUsuario($datos_compu['id_vinculo']);

		$datos_compu['nombre_apellido'] = Usuarios::getNombreDePila($id_usuario);

		return $datos_compu;
	}

}
?>