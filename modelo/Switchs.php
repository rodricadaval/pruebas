<?php

class Switchs {

	public static function claseMinus()
	{
		return strtolower(get_class());
	}

	public function listarCorrecto($datos_extra = "")
	{

		$data = null;

		$inst_table = BDD::getInstance()->query("select * ,
			'<a id=\"modificar_sector_switch\" class=\"pointer_mon\"id_switch=\"' || id_switch || '\"><i class=\"circular inverted black small sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"agregar_descripcion_switch\" class=\"pointer_cpu\"id_switch=\"' || id_switch || '\">
			<i class=\"circular inverted blue small book icon\" title=\"Ver o editar descripcion\"></i></a>
			<a id=\"modificar_ip_switch\" class=\"pointer_cpu\"id_switch=\"' || id_switch || '\"><i class=\"circular inverted green small bullseye icon\" title=\"Editar IP\"></i></a>
			<a id=\"eliminar_switch\" class=\"pointer_mon\"id_switch=\"' || id_switch || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>'
			as m from system.". self::claseMinus()." where estado = 1");

		$todo  = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++)
		{

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor)
			{

				switch ($campo)
				{
					case 'id_switch_desc':
						$arrayAsoc_desc = Switch_desc::dameDatos($valor);

						foreach ($arrayAsoc_desc as $camp => $value)
					{
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

					default:
						# code...
												break;
				}

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
		$id_tipo_producto = array_search("Switch", $tipos);

		$inst_table = BDD::getInstance()->query("select * ,
			'<a id=\"modificar_sector_switch\" class=\"pointer_mon\"id_switch=\"' || id_switch || '\"><i class=\"circular inverted black small sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"agregar_descripcion_switch\" class=\"pointer_cpu\"id_switch=\"' || id_switch || '\">
			<i class=\"circular inverted blue small book icon\" title=\"Ver o editar descripcion\"></i></a>
			<a id=\"modificar_ip_switch\" class=\"pointer_cpu\"id_switch=\"' || id_switch || '\"><i class=\"circular inverted green small bullseye icon\" title=\"Editar IP\"></i></a>
			<a id=\"eliminar_switch\" class=\"pointer_mon\"id_switch=\"' || id_switch || '\"><i class=\"circular inverted red small trash icon\" title=\"Eliminar\"></i></a>'
			as m from system.". self::claseMinus()." where estado = 1 AND id_vinculo IN (select id_vinculo from system.vinculos where id_sector=1 AND id_tipo_producto='$id_tipo_producto')");

		$todo  = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++)
		{

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor)
			{

				switch ($campo)
				{
					case 'id_switch_desc':
						$arrayAsoc_desc = Switch_desc::dameDatos($valor);

						foreach ($arrayAsoc_desc as $camp => $value)
					{
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

					default:
						# code...
												break;
				}

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

	public function dameComboBoxCrear()
	{

		$html_view = "<p>Rellene los campos deseados</p>";

		$table = BDD::getInstance()->query("select * from system.". self::claseMinus()." where estado=1");

		$html_view .= "<select id='select_switch' name='switch'>";
		$first = true;
		$inst_marca = new Marcas();

		while ($fila_switch = $table->_fetchRow())
		{

			if ($first)
			{
				$html_view = $html_view."<option selected='selected' value=".$fila_switch['modelo'].">".$fila_switch['modelo']."</option>";
				$first     = false;
			}
			else
			{
				$html_view = $html_view."<option value=".$fila_switch['modelo'].">".$fila_switch['modelo']."</option>";
			}
		}

		$html_view .= "</select>";
		return $html_view;
	}

	public function dameDatos($id)
	{
		$fila = BDD::getInstance()->query("select * from system.". self::claseMinus()." where id_switch = '$id' ")->_fetchRow();
		foreach ($fila as $campo => $valor)
		{
			if ($campo == "marca")
			{
				$fila['marca'] = Marcas::getNombre($valor);
			}
			else
			{
				$fila[$campo] = $valor;
			}
		}
		return $fila;
	}

	public function agregar($datos)
	{

		$id_switch_desc = Switch_desc::buscar_id_por_marca_modelo($datos['marca'], $datos['modelo']);
		$nro_serie      = strtoupper($datos['num_serie']);
		$values         = $datos['id_vinculo'].",".$id_switch_desc;

		if (isset($datos['ip']))
		{
			$ip = $datos['ip'];
		}
		else
		{
			$ip = "";
		}

		if ( ! BDD::getInstance()->query("INSERT INTO system.switchs (num_serie,ip,id_vinculo,id_switch_desc) VALUES ('$nro_serie','$ip',$values)")->get_error())
		{
			$valor_seq_actual_switchs = BDD::getInstance()->query("select nextval('system.switchs_id_switch_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_switchs--;
			BDD::getInstance()->query("select setval('system.switchs_id_switch_seq'::regclass,'$valor_seq_actual_switchs')");
			return $valor_seq_actual_switchs;

		}
		else
		{
			var_dump(BDD::getInstance());
			return 0;}
	}

	public function no_existe($nro)
	{

		if (BDD::getInstance()->query("select num_serie from system.". self::claseMinus()." where num_serie = '$nro' and estado=1 ")->get_count())
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}

	public function getByID($id)
	{
		$datos = BDD::getInstance()->query("select * from system.". self::claseMinus()." where id_switch = '$id' ")->_fetchRow();
		foreach ($datos as $key => $value)
		{
			if ($key == "id_vinculo")
			{
				$id_usuario    = Vinculos::getIdUsuario($value);
				$datos_usuario = Usuarios::getByID($id_usuario);
				$id_cpu        = Vinculos::getIdCpu($value);
				$datos_usuario['num_serie_cpu'] = Computadoras::getSerie($id_cpu);
				$datos_usuario['nombre_area'] = Areas::getNombre($datos_usuario['area']);
			}
		}
		return array_merge($datos, $datos_usuario);

	}

	public function modificarSwitch($datos)
	{
		$inst_vinc = new Vinculos();

		return $inst_vinc->modificarDatos($datos);
	}

	public function eliminarSwitch($id)
	{
		if ( ! BDD::getInstance()->query("DELETE FROM system.switchs where id_switch='$id' ")->get_error())
		{
			return 1;
		}
		else
		{
			return 0;}
	}

	public function eliminarLogico($datos)
	{
		$id       = $datos['id_switch'];
		$detalle  = $datos['detalle_baja'];
		$tipos    = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Switch", $tipos);
		$tabla    = "system.switchs";
		$campo_pk = "id_switch";

		if ( ! BDD::getInstance()->query("UPDATE system.switchs SET descripcion = '$detalle' where id_switch = '$id'")->get_error())
		{
			if ( ! BDD::getInstance()->query("SELECT system.baja_logica_producto('$id','$id_tipo_producto','$tabla','$campo_pk')")->get_error())
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

	public function agregarDescripcion($datos)
	{
		$id = $datos['id_switch'];
		$detalle = $datos['descripcion'];

		if ( ! BDD::getInstance()->query("UPDATE system.switchs SET descripcion = '$detalle' where id_switch = '$id'")->get_error())
		{
			return 1;
		}
		else
		{
			var_dump(BDD::getInstance());return 0;}
	}

	public function cambiarIp($datos)
	{
		$id = $datos['id_switch'];
		$detalle = $datos['ip'];

		if ( ! BDD::getInstance()->query("UPDATE system.switchs SET ip = '$detalle' where id_switch = '$id'")->get_error())
		{
			return 1;
		}
		else
		{
			var_dump(BDD::getInstance());return 0;}
	}
}
?>