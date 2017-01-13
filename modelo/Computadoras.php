<?php

class Computadoras {

	public static function claseMinus()
	{
		return strtolower(get_class());
	}

	public function listarCorrecto($datos_extra = "")
	{

		$data = null;

		$inst_table = BDD::getInstance()->query("
			select * ,
			'<a id=\"modificar_sector_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"black large sitemap icon\" title=\"Cambiar Sector \"></i>
			</a>
			<a id=\"modificar_tipo_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"green large edit icon\" title=\"Cambiar Tipo\"></i>
			</a>
			<a id=\"modificar_usuario_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"purple large user icon\" title=\"Asignar un Usuario\"></i>
			</a>
			<a id=\"ver_productos\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora ||'\"num_serie=\"' || num_serie || '\">
			<i class=\"orange large browser icon\" title=\"Ver sus componentes\"></i>
			</a>
			<a id=\"agregar_descripcion_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"blue large book icon\" title=\"Ver o editar descripcion\"></i>
			</a>
			<a id=\"desasignar_usuario_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"large minus outline icon\" title=\"Liberar Computadora\"></i>
			</a>
			<a id=\"generar_memorandum\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i title=\"Generar Memorandum\" class=\"orange file pdf outline large icon\"></i>
			</a>
			<a id=\"cambiar_num_serie\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"purple large edit icon\" title=\"Cambiar num de serie\"></i>
			</a>
			<a id=\"eliminar_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"red large trash icon\" title=\"Dar de baja\"></i>
			</a>'
			as m from system.".self::claseMinus()."
			where estado = 1 and id_computadora <> 1");

		$todo  = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++)
		{

			$data[$i] = $todo[$i];foreach ($data[$i] as $campo => $valor)
			{
				switch ($campo)
				{
					case 'id_computadora_desc':
						$arrayAsoc_desc = Computadora_desc::dameDatos($valor);

						foreach ($arrayAsoc_desc as $camp => $value)
					{
							if ($camp == "slots")
						{
								$slotsTotales = $value;
							}
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_vinculo':
						$arrayAsoc_vinculo = Vinculos::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value)
					{
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_computadora':
						$id_cpu = $valor;
						break;

					default:
						# code...
						break;
				}
			}
			$data[$i]["slots_libres"] = self::getSlotsLibres($slotsTotales, $id_cpu);
			if ($data[$i]["nombre_apellido"] == "Sin usuario")
			{
				$data[$i]["nombre_apellido"] = "-";
			}
		}

		if ($datos_extra[0] == "json")
		{
			echo json_encode($data);
		}
		else
		{
			return $data;
		}
	}

	public function listarEnStock($datos_extra = "")
	{

		$data = null;

		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Computadora", $tipos);

		$inst_table = BDD::getInstance()->query("
			select * ,
			'<a id=\"modificar_sector_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"black large sitemap icon\" title=\"Cambiar Sector \"></i>
			</a>
			<a id=\"modificar_tipo_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"green large edit icon\" title=\"Cambiar Tipo\"></i>
			</a>
			<a id=\"modificar_usuario_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"purple large user icon\" title=\"Asignar un Usuario\"></i>
			</a>
			<a id=\"ver_productos\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora ||'\"num_serie=\"' || num_serie || '\">
			<i class=\"orange large browser icon\" title=\"Ver sus componentes\"></i>
			</a>
			<a id=\"agregar_descripcion_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i class=\"blue large book icon\" title=\"Ver o editar descripcion\"></i>
			</a>
			<a id=\"generar_memorandum\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\">
			<i title=\"Generar Memorandum\" class=\"orange file pdf outline large icon\"></i>
			</a>
			<a id=\"eliminar_computadora\" class=\"pointer_cpu\"id_computadora=\"' || id_computadora || '\"><i class=\"red large trash icon\" title=\"Dar de baja\"></i></a>' as m
			from system.".self::claseMinus()."
			where estado = 1 and id_computadora <> 1 AND id_vinculo IN (select id_vinculo from system.vinculos where id_usuario=1 AND id_cpu=1 AND id_tipo_producto='$id_tipo_producto')");

		$todo  = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++)
		{

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor)
			{

				switch ($campo)
				{
					case 'id_computadora_desc':
						$arrayAsoc_desc = Computadora_desc::dameDatos($valor);

						foreach ($arrayAsoc_desc as $camp => $value)
					{
							if ($camp == "slots")
						{
								$slotsTotales = $value;
							}
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_vinculo':
						$arrayAsoc_vinculo = Vinculos::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value)
					{
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_computadora':
						$id_cpu = $valor;
						break;

					default:
						# code...
						break;
				}
			}
			$data[$i]["slots_libres"] = self::getSlotsLibres($slotsTotales, $id_cpu);
			if ($data[$i]["nombre_apellido"] == "Sin usuario")
			{
				$data[$i]["nombre_apellido"] = "-";
			}
		}

		if ($datos_extra[0] == "json")
		{
			echo json_encode($data);
		}
		else
		{
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

	public function agregar_computadora($datos)
	{

		$id_computadora_desc = Computadora_desc::buscar_id_por_marca_modelo($datos['marca'], $datos['modelo']);
		$nro_serie = strtoupper($datos['num_serie']);
		$clase     = $datos['clase'];
		$values    = $datos['id_vinculo'].",".$id_computadora_desc;

		if ( ! BDD::getInstance()->query("INSERT INTO system.computadoras (num_serie,clase,id_vinculo,id_computadora_desc) VALUES ('$nro_serie','$clase',$values)")->get_error())
		{
			$valor_seq_actual_computadoras = BDD::getInstance()->query("select nextval('system.computadoras_id_computadora_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_computadoras--;
			BDD::getInstance()->query("select setval('system.computadoras_id_computadora_seq'::regclass,'$valor_seq_actual_computadoras')");
			return $valor_seq_actual_computadoras;

		}
		else
		{
			var_dump(BDD::getInstance());
			return 0;}
	}

	public function getSerie($id)
	{
		return BDD::getInstance()->query("select num_serie from system.computadoras where id_computadora='$id'")->_fetchRow()['num_serie'];
	}

	public function getIdVinculoBySerie($serie)
	{
		return BDD::getInstance()->query("select id_vinculo from system.computadoras where num_serie = '$serie' ")->_fetchRow()['id_vinculo'];
	}

	public function getIdVinculoByIdCpu($id)
	{
		return BDD::getInstance()->query("select id_vinculo from system.computadoras where id_computadora = '$id' ")->_fetchRow()['id_vinculo'];
	}

	public function cambiarTipo($datos)
	{
		$clase = $datos['clase'];
		$id = $datos['id_computadora'];
		if (BDD::getInstance()->query("UPDATE system.computadoras SET clase='$clase' where id_computadora='$id'")->get_error())
		{
			return "false";
		}
		else
		{
			return "true";}
	}

	public function getIdBySerie($serie)
	{
		return BDD::getInstance()->query("select id_computadora from system.computadoras where num_serie = '$serie' ")->_fetchRow()['id_computadora'];
	}

	public function dameSelect_clase($clase = "", $sos = "")
	{

		return Tipos_Computadoras::dameSelect_clase($clase, $sos);
	}

	public function dameSelect_button_radio_clase($clase = "", $sos = "")
	{

		return Tipos_Computadoras::dameSelect_button_radio_clase($clase, $sos);
	}

	public function dameSelect($id = "", $sos = "")
	{
		$table = BDD::getInstance()->query("select num_serie, id_computadora from system.".self::claseMinus()." where estado = 1");
		if ($id != "")
		{
			$html_view = "<select id="."select_computadoras"."_".$sos." name='id_computadora'>";

		}
		else if ($id == "")
		{
			$html_view = "<select id="."select_computadoras"."_".$sos." name='id_computadora'>";
			$html_view .= "<option selected='selected' value=''>Seleccione computadora</option>";
		}

		while ($fila_computadora = $table->_fetchRow())
		{

			if ($fila_computadora['id_computadora'] == $id)
			{
				$html_view .= "<option selected='selected' value=".$fila_computadora['id_computadora'].">".$fila_computadora['num_serie']."</option>";
			}
			else if ($fila_computadora['id_computadora'] != "")
			{
				$html_view .= "<option value=".$fila_computadora['id_computadora'].">".$fila_computadora['num_serie']."</option>";
			}

		}

		$html_view = $html_view."</select>";
		return $html_view;
	}

	public function dameListaDeUsuario($id_usuario)
	{

		$lista_con_datos = null;

		$tipos   = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Computadora", $tipos);
		$usuario = "";
		$usuario = Usuarios::getNombreDePila($id_usuario);

		$lista = BDD::getInstance()->query("SELECT id_pk_producto FROM system.vinculos where id_tipo_producto='$id_tipo_producto' and id_usuario='$id_usuario' AND estado=1 ")->_fetchAll();
		$i = 0;
		foreach ($lista as $campo)
		{
			$lista_con_datos[$i] = self::getByID($campo['id_pk_producto']);
			$lista_con_datos[$i]['usuario'] = $usuario;
			$i++;
		}
		return self::generarListadoDeUsuario($lista_con_datos);
	}

	public function generarListadoDeUsuario($listado)
	{

		$html_view = "";
		$html_view .= "<fieldset>";
		$html_view .= "<h4>Computadora</h4>";
		$html_view .= "<table class='table table-condensed' id='tabla_productos_user'>";
		$html_view .= "<tr>";
		$html_view .= "<th>Serie</th>";

		if ($listado[0]['usuario'] == "Sala Servidores")
		{
			$html_view .= "<th>Nombre</th>";
		}

		$html_view .= "<th>Marca</th>
					   <th>Modelo</th>
					   <th>Slots Libres</th>
					   <th>Tipo</th>";
		$html_view .= "</tr>";

		if ($listado == null)
		{
			$html_view .= "<tr>";
			$html_view .= "<td colspan='5'>No tiene cpus</td>";
			$html_view .= "</tr>";
		}
		else
		{

			foreach ($listado as $fila => $contenido)
			{
				$html_view .= "<tr>";

				$datos_desc = Computadora_desc::dameDatos($contenido['id_computadora_desc']);

				$html_view .= "<td>".$contenido['num_serie']."</td>";
				if ($contenido['usuario'] == "Sala Servidores")
				{
					$html_view .= "<td>".$contenido['descripcion']."</td>";
				}
				$html_view .= "<td>".$datos_desc['marca']."</td>";
				$html_view .= "<td>".$datos_desc['modelo']."</td>";
				$tipos = Tipos_Computadoras::get_rel_campos();
				$tipo  = array_search($contenido['clase'], $tipos);
				$html_view .= "<td>".self::getSlotsLibres($datos_desc['slots'], $contenido['id_computadora'])."</td>";
				$html_view .= "<td>".$tipo."</td>";

				$html_view .= "</tr>";
			}
		}

		$html_view .= "</table>";
		$html_view .= "</fieldset>";
		return $html_view;
	}

	public function dameListadoMemoDeCpu($id)
	{

		$lista_con_datos = null;

		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Computadora", $tipos);

		$i = 0;

		$lista2 = BDD::getInstance()->query("SELECT id_vinculo FROM system.vinculos WHERE id_pk_producto='$id' AND id_tipo_producto='$id_tipo_producto' AND estado=1 ")->_fetchAll();

		$lista_con_datos[0] = Vinculos::getByID($lista2[0]['id_vinculo']);

		$lista = BDD::getInstance()->query("SELECT id_vinculo FROM system.vinculos WHERE id_cpu='$id' AND estado=1 ")->_fetchAll();

		$i++;

		foreach ($lista as $campo)
		{
			$lista_con_datos[$i] = Vinculos::getByID($campo['id_vinculo']);
			$i++;
		}

		return self::generarListadoMemorandum($lista_con_datos);
	}

	public function generarListadoMemorandum($listado)
	{

		$fila = null;
		$contenido = null;

		$html_view = "";
		$html_view .= "<table class='table table-condensed' id='tabla_listado_memorandum_cpu'>";
		$html_view .= "<tr>";
		$html_view .= "<th></th>";
		$html_view .= "<th>Producto</th>
					   <th>Detalle</th>";
		$html_view .= "</tr>";

		if (count($listado) == 0)
		{
			$html_view .= "<tr>";
			$html_view .= "<td colspan='3'>No tiene productos</td>";
			$html_view .= "</tr>";
		}
		else
		{

			foreach ($listado as $fila => $contenido)
			{
				$html_view .= "<tr>";
				$vinculo = $contenido['id_vinculo'];

				$html_view .= "<td><input type='checkbox' id='productos_seleccionados' value='$vinculo'></td>";
				$html_view .= "<td>".$contenido['producto']."</td>";
				switch ($contenido['producto'])
				{
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
		}

		$html_view .= "</table>";
		return $html_view;

	}

	public function tieneSlotsLibres($id)
	{
		$id_desc          = 0;
		$id_desc          = BDD::getInstance()->query("select id_computadora_desc from system.computadoras where id_computadora = '$id' ")->_fetchRow()['id_computadora_desc'];		
		$slotsTotales     = Computadora_desc::getSlots($id_desc);
		$tipos            = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Memoria", $tipos);
		$cantidadUsada    = BDD::getInstance()->query("select count(*) as cantidad from system.vinculos where id_cpu = '$id' AND id_tipo_producto = '$id_tipo_producto' and estado=1")->_fetchRow()['cantidad'];		

		if ($cantidadUsada < $slotsTotales)
		{
			return "true";
		}
		else
		{
			return "false";
		}
	}

	public function getSlotsLibres($tot, $id)
	{
		$usados = 0;
		$tipos  = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Memoria", $tipos);
		$usados = BDD::getInstance()->query("select count(*) as cantidad from system.vinculos where id_cpu = '$id' AND id_tipo_producto = '$id_tipo_producto' and estado=1")->_fetchRow()['cantidad'];
		return $tot - $usados;
	}

	public function tieneEspacioMemLibre($datos)
	{
		$id               = $datos['id_cpu'];
		$id_desc          = 0;
		$capacidadEnUso   = 0;
		$id_desc          = BDD::getInstance()->query("select id_computadora_desc from system.computadoras where id_computadora = '$id' ")->_fetchRow()['id_computadora_desc'];
		$memMax           = Computadora_desc::getMemMax($id_desc);
		$array_potencias  = Unidades::get_rel_potencia();
		$exponente        = array_search("GB", $array_potencias);
		$memMax           = $memMax * pow(1024, $exponente);
		$tipos            = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Memoria", $tipos);
		$tabla_mem        = BDD::getInstance()->query("select id_pk_producto as id_memoria from system.vinculos where id_cpu = '$id' AND id_tipo_producto = '$id_tipo_producto' and estado=1")->_fetchAll();

		foreach ($tabla_mem as $fila_mem)
		{
			$capacidadEnUso += Memorias::getCapacidadEnMegas($fila_mem['id_memoria']);
		}

		$capacidadTotal = $capacidadEnUso + Memorias::getCapacidadEnMegas($datos['id_memoria']);

		if($capacidadTotal <= $memMax)
		{
			return "true";
		}
		else
		{
			return "false";
		}
	}

	public function cantidadMemoriaLibre($datos)
	{
		$id               = $datos['id_cpu'];
		$id_desc          = 0;
		$capacidadEnUso   = 0;
		$id_desc          = BDD::getInstance()->query("select id_computadora_desc from system.computadoras where id_computadora = '$id' ")->_fetchRow()['id_computadora_desc'];
		$memMax           = Computadora_desc::getMemMax($id_desc);
		$array_potencias  = Unidades::get_rel_potencia();
		$exponente        = array_search("GB", $array_potencias);
		$memMax           = $memMax * pow(1024, $exponente);
		$tipos            = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Memoria", $tipos);
		$tabla_mem        = BDD::getInstance()->query("select id_pk_producto as id_memoria from system.vinculos where id_cpu = '$id' AND id_tipo_producto = '$id_tipo_producto' and estado=1")->_fetchAll();

		foreach ($tabla_mem as $fila_mem){
			$capacidadEnUso += Memorias::getCapacidadEnMegas($fila_mem['id_memoria']);
		}
		return $memMax - $capacidadEnUso;
	}

	public function dameSelectDeUsuario($id = "", $sos = "")
	{

		$nuevo    = true;
		$consulta = "";
		$id_compu_que_tenia = null;

		if ($sos == "dialog_mod_cpu_sin_usr")
		{
			$datos     = BDD::getInstance()->query("select id_sector,id_cpu from system.vinculos where id_vinculo = '$id' ")->_fetchRow();
			$id_sector = $datos['id_sector'];
			$id_compu_que_tenia = $datos['id_cpu'];
			$tableVinc = BDD::getInstance()->query("select distinct id_pk_producto from system.vinculos where id_usuario = 1 AND id_sector = '$id_sector' AND id_tipo_producto = 4 AND estado=1");
			$nuevo     = false;
			$consulta .= 1;
		}
		else
		{
			$tableVinc = BDD::getInstance()->query("select distinct id_pk_producto from system.vinculos where id_usuario = '$id' AND id_tipo_producto = 4 AND estado=1");
		}

		$html_view = "<select id='select_computadoras_".$sos."' name='id_computadora'>";

		if (($id == "" || $id == 1 || $tableVinc->get_count() == 0) && $sos != "dialog_mod_cpu_sin_usr")
		{
			$html_view .= "<option value='1'>Sin Cpu</option>";
		}
		else
		{

			while ($fila_vinc = $tableVinc->_fetchRow())
			{

				$id_cpu = $fila_vinc['id_pk_producto'];

				if ($nuevo)
				{
					$consulta .= $id_cpu;
					$nuevo = false;
				}
				else
				{
					$consulta .= ",".$id_cpu;
				}
			}

			$table = BDD::getInstance()->query("select num_serie,id_computadora from system.computadoras where id_computadora IN ($consulta)");

			while ($fila_computadora = $table->_fetchRow())
			{

				if ($id_compu_que_tenia == $fila_computadora['id_computadora'])
				{
					$html_view .= "<option selected='selected' value='".$fila_computadora['id_computadora']."'>".$fila_computadora['num_serie']."</option>";
				}
				else
				{
					$html_view .= "<option value='".$fila_computadora['id_computadora']."'>".$fila_computadora['num_serie']."</option>";
				}
			}
		}
		$html_view = $html_view."</select>";

		return $html_view;
	}

	public function modificar($datos)
	{
		$inst_vinc = new Vinculos();
		$datos['id_usuario'] = Usuarios::getIdByNombre($datos['nombre_usuario']);
		unset($datos['nombre_usuario']);
		$clase = $datos['clase'];
		$id = $datos['id_cpu'];
		BDD::getInstance()->query("UPDATE system.computadoras SET clase='$clase' where id_computadora='$id'");
		return $inst_vinc->modificarDatos($datos);
	}

	public function modificarSectorConAsignados($datos)
	{
		$inst_vinc = new Vinculos();
		if ($inst_vinc->cambiarSector($datos))
		{
			if ($inst_vinc->cambiarSectorDeAsignados($datos))
			{
				return "true";
			}
			else
			{
				return "false";
			}
		}
		else
		{
			return "false";
		}
	}

	public function modificarSectorSinAsignados($datos)
	{
		$inst_vinc = new Vinculos();
		if ($inst_vinc->cambiarSector($datos))
		{
			if ($inst_vinc->desasignarDeCpu($datos))
			{
				return "true";
			}
			else
			{
				return "false";
			}
		}
		else
		{
			return "false";
		}
	}

	public function modificarUsuarioConAsignados($datos)
	{
		if ( ! isset($datos['']))
		//$datos['id_sector'] = Usuarios::dame_id_area($datos['id_usuario']);
		{
			$datos['id_cpu'] = $datos['id_computadora'];
		}

		unset($datos['id_computadora']);
		$datos['id_vinculo'] = self::getIdVinculoByIdCpu($datos['id_cpu']);

		$inst_vinc = new Vinculos();
		if ($inst_vinc->cambiarUsuarioYSector($datos))
		{
			if ($inst_vinc->cambiarUsuarioYSectorDeAsignados($datos))
			{
				return "true";
			}
			else
			{
				return "false";
			}
		}
		else
		{
			return "false";
		}
	}

	public function modificarUsuarioSinAsignados($datos)
	{
		//$datos['id_sector'] = Usuarios::dame_id_area($datos['id_usuario']);
		$id_usuario = $datos['id_usuario'];
		$datos['id_cpu'] = $datos['id_computadora'];
		unset($datos['id_computadora']);

		$datos['id_vinculo'] = self::getIdVinculoByIdCpu($datos['id_cpu']);

		$inst_vinc = new Vinculos();
		if ($inst_vinc->cambiarUsuarioYSector($datos))
		{
			if ($inst_vinc->desasignarDeCpu($datos))
			{
				return "true";
			}
			else
			{
				return "false";
			}
		}
		else
		{
			return "false";
		}
	}

	public function quitarUsuarioConAsignados($datos)
	{

		$inst_vinc = new Vinculos();

		$id_cpu = $inst_vinc->getIdCpuDeLaPc($datos['id_vinculo']);

		if ($inst_vinc->quitarUsuarioDeAsignados($id_cpu))
		{
			return $inst_vinc->liberar($datos['id_vinculo']);
		}
		else
		{
			return "false";
		}
	}

	public function quitarUsuarioSinAsignados($datos)
	{
		$inst_vinc = new Vinculos();

		$id_cpu = $inst_vinc->getIdCpuDeLaPc($datos['id_vinculo']);

		if ($inst_vinc->desasignarDeCpu($datos))
		{
			return $inst_vinc->liberar($datos['id_vinculo']);
		}
		else
		{
			return "false";
		}
	}

	public function agregarDescripcion($datos)
	{
		$id = $datos['id_computadora'];
		$detalle = $datos['descripcion'];

		if ( ! BDD::getInstance()->query("UPDATE system.computadoras SET descripcion = '$detalle' where id_computadora = '$id'")->get_error())
		{
			return 1;
		}
		else
		{
			var_dump(BDD::getInstance());return 0;}
	}

	public function modificarNumSerie($datos)
	{
		$id = $datos['id_computadora'];
		$num_serie = $datos['num_serie'];
		$num_serie = trim($num_serie);

		if ( ! BDD::getInstance()->query("UPDATE system.computadoras SET num_serie = '$num_serie' where id_computadora = '$id'")->get_error())
		{
			return 1;
		}
		else
		{
			var_dump(BDD::getInstance());return 0;}
	}

	public function eliminarLogico($datos)
	{
		$id       = $datos['id_computadora'];
		$detalle  = $datos['detalle_baja'];
		$tipos    = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Computadora", $tipos);
		$tabla    = "system.computadoras";
		$campo_pk = "id_computadora";

		if ( ! BDD::getInstance()->query("UPDATE system.computadoras SET descripcion = '$detalle' where id_computadora = '$id'")->get_error())
		{
			if ( ! BDD::getInstance()->query("SELECT system.baja_logica_producto('$id','$id_tipo_producto','$tabla','$campo_pk')")->get_error())
			{
				if ( ! BDD::getInstance()->query("SELECT system.limpiar_productos_asoc_a_cpu('$id')")->get_error())
				{
					return 1;
				}
				else
				{
					var_dump(BDD::getInstance());return 0;}
			}
			else
			{
				var_dump(BDD::getInstance());return 0;}
		}
		else
		{
			var_dump(BDD::getInstance());return 0;}
	}

	public function eliminar($id)
	{
		if ( ! BDD::getInstance()->query("DELETE FROM system.computadoras where id_computadora='$id' ")->get_error())
		{
			return 1;
		}
		else
		{
			return 0;}
	}

/*	public function liberar($id){
$id_vinculo = BDD::getInstance()->query("select id_vinculo from system.computadoras where id_computadora='$id' ")->_fetchRow()['id_vinculo'];
$inst_vinc = new Vinculos();
if($inst_vinc->liberar($id_vinculo) == "true"){
$datos['id_vinculo'] = $id_vinculo);
if($inst_vinc->desasignarDeCpu($datos)){
echo "true";
}
else{
echo "false";
}
}
else{
echo "false";
}
}*/

	public function getByID($id)
	{
		$datos_compu = BDD::getInstance()->query("select * from system.".self::claseMinus()." where id_computadora = '$id' ")->_fetchRow();

		$id_usuario = Vinculos::getIdUsuario($datos_compu['id_vinculo']);

		$datos_compu['nombre_apellido'] = Usuarios::getNombreDePila($id_usuario);

		return $datos_compu;
	}

	public function getConSectorById($id)
	{
		$datos_compu = BDD::getInstance()->query("select * from system.".self::claseMinus()." where id_computadora = '$id' ")->_fetchRow();

		$datos_compu['id_sector'] = Vinculos::getIdSector($datos_compu['id_vinculo']);
		$datos_compu['id_usuario'] = Vinculos::getIdUsuario($datos_compu['id_vinculo']);

		return $datos_compu;
	}

}
?>