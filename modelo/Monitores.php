<?php

class Monitores {

	public static function claseMinus()
	{
		return strtolower(get_class());
	}

	public function listarTodos()
	{

		$inst_table = BDD::getInstance()->query("select * , '<a id=\"modificar_usuario_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"blue large user icon\" title=\"Asignar/Cambiar usuario\"></i></a><a id=\"modificar_cpu_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"black large laptop icon\" title=\"Asignar/Cambiar Computadora\"></i></a><a id=\"modificar_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"green large sitemap icon\" title=\"Editar sólo Sector\"></i></a> <a id=\"eliminar_monitor\" class=\"pointer\"id_monitor=\"' || id_monitor || '\"><i class=\"red large trash icon\" title=\"Eliminar\"></i></a>' as m from system.". self::claseMinus()." where estado = 1");
		$i = 0;
		while ($fila = $inst_table->_fetchRow())
		{
			foreach ($fila as $campo => $valor)
			{
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
		echo json_encode($data);
	}

	public function listarCorrecto($datos_extra = "")
	{

		$data = null;

		$inst_table = BDD::getInstance()->query("select * ,
			'<a id=\"modificar_sector_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"black large sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"modificar_cpu_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"blue large laptop icon\" title=\"Asignar una Computadora\"></i></a>
			<a id=\"modificar_usuario_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"purple large user icon\" title=\"Asignar un Usuario\"></i></a>
			<a id=\"desasignar_todo_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"green large minus outline icon\" title=\"Liberar Monitor (Quita el usuario y el cpu asignados) \"></i></a>
			<a id=\"eliminar_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"red large trash icon\" title=\"Eliminar\"></i></a>'
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
					case 'id_monitor_desc':
						$arrayAsoc_desc = Monitor_desc::dameDatos($valor);

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
		Probablemente es equivalente a listarEnStock pero tiene todos los datos en una view
	*/
	public function disponibles()
	{
		$datos = BDD::getInstance()->query("SELECT *,'<a id=\"asignar_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"green large edit outline icon\" title=\"Asignar monitor\"></i></a>' as action FROM monitores_completos")->_fetchAll();

		$html_view = "";
		$html_view .= "<fieldset>";
		$html_view .= "<h4>Monitores</h4>";
		$html_view .= "<table class='table table-condensed' id='tabla_monitores_disponibles'>";
		$html_view .= "<tr>";
		$html_view .= "<th>Marca</th>
					   <th>Num Serie</th>
					   <th>Sector</th>
					   <th>Action</th>";
		$html_view .= "</tr>";

		if ($datos == null)
		{
			$html_view .= "<tr>";
			$html_view .= "<td colspan='3'>No hay monitores disponibles</td>";
			$html_view .= "</tr>";
		}
		else
		{

			foreach ($datos as $fila => $contenido)
			{
				$html_view .= "<tr>";

				$html_view .= "<td>".$contenido['Marca']."</td>";
				$html_view .= "<td>".$contenido['num_serie']."</td>";
				$html_view .= "<td>".$contenido['Sector']."</td>";
				$html_view .= "<td>".$contenido['action']."</td>";

				$html_view .= "</tr>";
			}
		}

		$html_view .= "</table>";
		$html_view .= "</fieldset>";
		return $html_view;
		//return $datos;
	}

	public function listarEnStock($datos_extra = "")
	{

		$data = null;

		$inst_table = BDD::getInstance()->query("select * ,
			'<a id=\"modificar_sector_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"black large sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"modificar_cpu_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"blue large laptop icon\" title=\"Asignar una Computadora\"></i></a>
			<a id=\"modificar_usuario_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"purple large user icon\" title=\"Asignar un Usuario\"></i></a>
			<a id=\"eliminar_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"red large trash icon\" title=\"Eliminar\"></i></a>'
			as m from system.". self::claseMinus()." where estado = 1 AND id_vinculo IN (select id_vinculo from system.vinculos where id_usuario=1 AND id_cpu=1 AND id_tipo_producto=1)");

		$todo  = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++)
		{

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor)
			{

				switch ($campo)
				{
					case 'id_monitor_desc':
						$arrayAsoc_desc = Monitor_desc::dameDatos($valor);

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

	public function getNombre($id)
	{
		return BDD::getInstance()->query("select nombre from system.". self::claseMinus()." where id_monitor = '$id' ")->_fetchRow()['id_monitor'];
	}

	public function dameComboBoxCrear()
	{

		$html_view = "<p>Rellene los campos deseados</p>";

		$table = BDD::getInstance()->query("select * from system.". self::claseMinus()." where estado=1");

		$html_view .= "<select id='select_monitor' name='monitor'>";
		$first = true;
		$inst_marca = new Marcas();

		while ($fila_monitor = $table->_fetchRow())
		{

			if ($first)
			{
				$html_view = $html_view."<option selected='selected' value=".$fila_monitor['modelo'].">".$fila_monitor['modelo']."</option>";
				$first     = false;
			}
			else
			{
				$html_view = $html_view."<option value=".$fila_monitor['modelo'].">".$fila_monitor['modelo']."</option>";
			}
		}

		$html_view .= "</select>";
		return $html_view;
	}

	public function dameDatos($id)
	{
		$fila = BDD::getInstance()->query("select * from system.". self::claseMinus()." where id_monitor = '$id' ")->_fetchRow();
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

	public function dameListaDeUsuario($id_usuario)
	{

		$lista_con_datos = null;

		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Monitor", $tipos);

		$lista = BDD::getInstance()->query("SELECT id_pk_producto FROM system.vinculos where id_tipo_producto='$id_tipo_producto' AND id_usuario='$id_usuario' AND estado=1 ")->_fetchAll();
		$i = 0;
		foreach ($lista as $campo)
		{
			$lista_con_datos[$i] = self::getByID($campo['id_pk_producto']);
			$i++;
		}

		return self::generarListadoDeUsuario($lista_con_datos);
	}

	public function generarListadoDeUsuario($listado)
	{

		$html_view = "";
		$html_view .= "<fieldset>";
		$html_view .= "<h4>Monitor</h4>";
		$html_view .= "<table class='table table-condensed' id='tabla_productos_user'>";
		$html_view .= "<tr>";
		$html_view .= "<th>Serie</th>
					   <th>Marca</th>
					   <th>Modelo</th>
					   <th>Pulgadas</th>
					   <th>Serie Cpu</th>					   
					   <th>Action</th>";
		$html_view .= "</tr>";

		if ($listado == null)
		{
			$html_view .= "<tr>";
			$html_view .= "<td colspan='5'>No tiene monitores</td>";
			$html_view .= "</tr>";
		}
		else
		{

			foreach ($listado as $lista => $contenido)
			{
				$html_view .= "<tr>";

				$html_view .= "<td>".$contenido['num_serie']."</td>";

				$datos_desc = Monitor_desc::dameDatos($contenido['id_monitor_desc']);

				if ($datos_desc['pulgadas'] == "")
				{
					$datos_desc['pulgadas'] = "-";}

				$html_view .= "<td>".$datos_desc['marca']."</td>";
				$html_view .= "<td>".$datos_desc['modelo']."</td>";
				$html_view .= "<td>".$datos_desc['pulgadas']."</td>";
				$html_view .= "<td>".$contenido['num_serie_cpu']."</td>";
				$html_view .= "<td>".$contenido['action']."</td>";

				$html_view .= "</tr>";
			}
		}

		$html_view .= "</table>";
		$html_view .= "</fieldset>";
		return $html_view;
	}

	public function dameListaDeCpu($id_cpu)
	{

		$lista_con_datos = null;

		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Monitor", $tipos);

		$lista = BDD::getInstance()->query("SELECT id_pk_producto FROM system.vinculos where id_tipo_producto='$id_tipo_producto' AND id_cpu='$id_cpu' AND estado=1 ")->_fetchAll();
		$i = 0;
		foreach ($lista as $campo)
		{
			$lista_con_datos[$i] = self::getByID($campo['id_pk_producto']);
			$i++;
		}

		return self::generarListadoDeCpu($lista_con_datos);
	}

	public function generarListadoDeCpu($listado)
	{

		$html_view = "";
		$html_view .= "<fieldset>";
		$html_view .= "<h4>Monitor</h4>";
		$html_view .= "<table class='table table-condensed' id='tabla_productos_cpu'>";
		$html_view .= "<tr>";
		$html_view .= "<th>Serie</th>
					   <th>Marca</th>
					   <th>Modelo</th>
					   <th>Pulgadas</th>";
		$html_view .= "</tr>";

		if ($listado == null)
		{
			$html_view .= "<tr>";
			$html_view .= "<td colspan='4'>No tiene monitores</td>";
			$html_view .= "</tr>";
		}
		else
		{

			foreach ($listado as $lista => $contenido)
			{
				$html_view .= "<tr>";

				$html_view .= "<td>".$contenido['num_serie']."</td>";

				$datos_desc = Monitor_desc::dameDatos($contenido['id_monitor_desc']);

				if ($datos_desc['pulgadas'] == "")
				{
					$datos_desc['pulgadas'] = "-";}

				$html_view .= "<td>".$datos_desc['marca']."</td>";
				$html_view .= "<td>".$datos_desc['modelo']."</td>";
				$html_view .= "<td>".$datos_desc['pulgadas']."</td>";				

				$html_view .= "</tr>";
			}
		}

		$html_view .= "</table>";
		$html_view .= "</fieldset>";
		return $html_view;
	}

	public function agregar_monitor($datos)
	{

		$id_monitor_desc = Monitor_desc::buscar_id_por_marca_modelo($datos['marca'], $datos['modelo']);
		$nro_serie       = strtoupper($datos['num_serie']);
		$values          = $datos['id_vinculo'].",".$id_monitor_desc;

		if ( ! BDD::getInstance()->query("INSERT INTO system.monitores (num_serie,id_vinculo,id_monitor_desc) VALUES ('$nro_serie',$values)")->get_error())
		{
			$valor_seq_actual_monitores = BDD::getInstance()->query("select nextval('system.monitores_id_monitor_seq'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_monitores--;
			BDD::getInstance()->query("select setval('system.monitores_id_monitor_seq'::regclass,'$valor_seq_actual_monitores')");
			return $valor_seq_actual_monitores;

		}
		else
		{
			var_dump(BDD::getInstance());
			return 0;}
	}

	public function no_existe($nro)
	{

		if (BDD::getInstance()->query("select num_serie from system.". self::claseMinus()." where num_serie = '$nro' ")->get_count())
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
		$datos = BDD::getInstance()->query("select *,'<a id=\"agregar_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"green large plus outline icon\" title=\"Agregar monitor\"></i></a><a id=\"desasignar_todo_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"green large minus outline icon\" title=\"Liberar Monitor (Quita el usuario y el cpu asignados) \"></i></a>
			<a id=\"eliminar_monitor\" class=\"pointer_mon\"id_monitor=\"' || id_monitor || '\"><i class=\"red large trash icon\" title=\"Eliminar\"></i></a>' as action from system.". self::claseMinus()." where id_monitor = '$id' ")->_fetchRow();
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

	public function modificarMonitor($datos)
	{
		return Vinculos::modificarDatos($datos);
	}

	public function eliminarMonitor($id)
	{
		if ( ! BDD::getInstance()->query("DELETE FROM system.monitores where id_monitor='$id' ")->get_error())
		{
			return 1;
		}
		else
		{
			return 0;}
	}

	public function eliminarLogico($datos)
	{
		$id       = $datos['id_monitor'];
		$detalle  = $datos['detalle_baja'];
		$tipos    = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Monitor", $tipos);
		$tabla    = "system.monitores";
		$campo_pk = "id_monitor";

		if ( ! BDD::getInstance()->query("UPDATE system.monitores SET descripcion = '$detalle' where id_monitor = '$id'")->get_error())
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

	public function eliminarMonitorLogico($id)
	{
		if ( ! BDD::getInstance()->query("UPDATE system.monitores SET estado=0 where id_monitor='$id' ")->get_error())
		{
			return 1;
		}
		else
		{
			return 0;}
	}

	public function liberarMonitor($id)
	{
		$id_vinculo = BDD::getInstance()->query("select id_vinculo from system.monitores where id_monitor='$id' ")->_fetchRow()['id_vinculo'];
		$inst_vinc  = new Vinculos();
		echo $inst_vinc->liberar($id_vinculo);
	}
}
?>