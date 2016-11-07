<?php

class Memorias {

	public static function claseMinus()
	{
		return strtolower(get_class());
	}

	public function listarTodos()
	{

		$inst_table = BDD::getInstance()->query("select * , '<a id=\"modificar_usuario_memoria\" class=\"pointer\"id_memoria=\"' || id_memoria || '\"><i class=\"blue large user icon\" title=\"Asignar/Cambiar usuario\"></i></a><a id=\"modificar_cpu_memoria\" class=\"pointer\"$id_memoria=\"' || $id_memoria || '\"><i class=\"black large laptop icon\" title=\"Asignar/Cambiar Computadora\"></i></a><a id=\"modificar_memoria\" class=\"pointer\"$id_memoria=\"' || $id_memoria || '\"><i class=\"green large sitemap icon\" title=\"Editar sólo Sector\"></i></a> <a id=\"eliminar_memoria\" class=\"pointer\"id_memoria=\"' || id_memoria || '\"><i class=\"red large trash icon\" title=\"Eliminar\"></i></a>' as m from system.".self::claseMinus()." where estado = 1");
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
			'<a id=\"modificar_sector_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"black large sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"modificar_cpu_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"blue large laptop icon\" title=\"Asignar una Computadora\"></i></a>
			<a id=\"modificar_usuario_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"purple large user icon\" title=\"Asignar un Usuario\"></i></a>
			<a id=\"desasignar_todo_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"green large minus outline icon\" title=\"Liberar memoria (Quita el usuario y el cpu asignados) \"></i></a>
			<a id=\"eliminar_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"red large trash icon\" title=\"Eliminar\"></i></a>'
			as m from system.".self::claseMinus()." where estado = 1");

		$todo  = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++)
		{

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor)
			{

				switch ($campo)
				{
					case 'id_memoria_desc':
						$arrayAsoc_desc = Memoria_desc::dameDatos($valor);

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

					case 'id_capacidad':
						$arrayAsoc_vinculo = Capacidades::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value)
					{
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_unidad':
						$arrayAsoc_vinculo = Unidades::dameDatos($valor);

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
			$data[$i]['capacidad'] .= " ".$data[$i]['unidad'];
			if ($data[$i]["nombre_apellido"] == "Sin usuario")
			{
				$data[$i]["nombre_apellido"] = "-";
			}
		}
		//var_dump($data);

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
		$id_tipo_producto = array_search("Memoria", $tipos);

		$inst_table = BDD::getInstance()->query("select * ,
			'<a id=\"modificar_sector_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"black large sitemap icon\" title=\"Cambiar Sector \"></i></a>
			<a id=\"modificar_cpu_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"blue large laptop icon\" title=\"Asignar una Computadora\"></i></a>
			<a id=\"modificar_usuario_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"purple large user icon\" title=\"Asignar un Usuario\"></i></a>
			<a id=\"eliminar_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"red large trash icon\" title=\"Eliminar\"></i></a>'
			as m from system.".self::claseMinus()." where estado = 1 AND id_vinculo IN (select id_vinculo from system.vinculos where id_usuario=1 AND id_cpu=1 AND id_tipo_producto='$id_tipo_producto')");

		$todo  = $inst_table->_fetchAll();
		$total = $inst_table->get_count();

		for ($i = 0; $i < $total; $i++)
		{

			$data[$i] = $todo[$i];

			foreach ($data[$i] as $campo => $valor)
			{

				switch ($campo)
				{
					case 'id_memoria_desc':
						$arrayAsoc_desc = Memoria_desc::dameDatos($valor);

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

					case 'id_capacidad':
						$arrayAsoc_vinculo = Capacidades::dameDatos($valor);

						foreach ($arrayAsoc_vinculo as $camp => $value)
					{
							$data[$i][$camp] = $value;
						}
						break;

					case 'id_unidad':
						$arrayAsoc_vinculo = Unidades::dameDatos($valor);

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
			$data[$i]['capacidad'] .= " ".$data[$i]['unidad'];
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

	public function dameDatos($id)
	{
		$fila = BDD::getInstance()->query("select * from system.".self::claseMinus()." where $id_memoria = '$id' ")->_fetchRow();
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

		$id_memoria_desc = Memoria_desc::buscar_id_por_marca_tipo_y_velocidad($datos['marca'], $datos['tipo_mem'], $datos['velocidad']);

		$tipo_dimm = "'".$datos['tipo_dimm']."'";

		$values = $datos['id_vinculo'].",".$datos['capacidad'].",".$datos['unidad'].",".$id_memoria_desc.",".$tipo_dimm;

		if ( ! BDD::getInstance()->query("INSERT INTO system.memorias (id_vinculo,id_capacidad,id_unidad,id_memoria_desc,tipo_dimm) VALUES ($values)")->get_error())
		{
			$valor_seq_actual_memorias = BDD::getInstance()->query("select nextval('system.memorias_id_memoria_seq1'::regclass)")->_fetchRow()['nextval'];
			$valor_seq_actual_memorias--;
			BDD::getInstance()->query("select setval('system.memorias_id_memoria_seq1'::regclass,'$valor_seq_actual_memorias')");
			return $valor_seq_actual_memorias;

		}
		else
		{
			var_dump(BDD::getInstance());
			return 0;}
	}

	public function getByID($id)
	{
		$datos = BDD::getInstance()->query("select *, '<a id=\"agregar_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"green large plus outline icon\" title=\"Agregar memoria (Aperecen las memorias disponibles en stock) \"></i></a><a id=\"desasignar_todo_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"green large minus outline icon\" title=\"Liberar memoria (Quita el usuario y el cpu asignados) \"></i></a>
			<a id=\"eliminar_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"red large trash icon\" title=\"Eliminar\"></i></a>' as action from system.".self::claseMinus()." where id_memoria = '$id' ")->_fetchRow();
		foreach ($datos as $key => $value)
		{
			if ($key == "id_vinculo")
			{
				$id_usuario  = Vinculos::getIdUsuario($value);
				$datos_extra = Usuarios::getByID($id_usuario);
				$id_cpu      = Vinculos::getIdCpu($value);
				$datos_extra['num_serie_cpu'] = Computadoras::getSerie($id_cpu);
				$datos_extra['nombre_area'] = Areas::getNombre($datos_extra['area']);
			}
			if ($key == "id_capacidad")
			{
				$datos['capacidad'] = Capacidades::getNombre($value);
			}
			if ($key == "id_unidad")
			{
				$datos['unidad'] = Unidades::getNombre($value);
			}
		}
		return array_merge($datos, $datos_extra);
	}

	public function dameListaDeUsuario($id_usuario)
	{

		$lista_con_datos = null;

		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Memoria", $tipos);

		$lista = BDD::getInstance()->query("SELECT id_pk_producto FROM system.vinculos where id_tipo_producto='$id_tipo_producto' and id_usuario='$id_usuario' AND estado=1 ")->_fetchAll();
		$i = 0;
		foreach ($lista as $campo)
		{
			$lista_con_datos[$i] = self::getByID($campo['id_pk_producto']);
			//return var_dump($lista_con_datos);
			$i++;
		}
		return self::generarListadoDeUsuario($lista_con_datos);
	}

	public function generarListadoDeUsuario($listado)
	{

		$html_view = "";
		$html_view .= "<fieldset>";
		$html_view .= "<h4>Memorias</h4>";
		$html_view .= "<table class='table table-condensed' id='tabla_productos_user'>";
		$html_view .= "<tr>";
		$html_view .= "<th>Marca</th>
					   <th>Tipo</th>
					   <th>Capacidad</th>
					   <th>Velocidad (Mhz)</th>
					   <th>Serie Cpu</th>
					   <th>Action</th>";
		$html_view .= "</tr>";

		if ($listado == null)
		{
			$html_view .= "<tr>";
			$html_view .= "<td colspan='5'>No tiene memorias</td>";
			$html_view .= "</tr>";
		}
		else
		{

			foreach ($listado as $fila => $contenido)
			{
				$html_view .= "<tr>";
				foreach ($contenido as $campo => $valor) {
					# code...
				}
				$datos_desc = Memoria_desc::dameDatos($contenido['id_memoria_desc']);

				$html_view .= "<td>".$valor."</td>";
				$html_view .= "<td>".$datos_desc['tipo']."</td>";
				$html_view .= "<td>".$contenido['capacidad']." ".$contenido['unidad']."</td>";
				$html_view .= "<td>".$datos_desc['velocidad']."</td>";
				$html_view .= "<td>".$contenido['num_serie_cpu']."</td>";
				$html_view .= "<td>".$contenido['action']."</td>";

				$html_view .= "</tr>";
			}
			$html_view .= "<tr id='total'>";
			$html_view .= "<td colspan='3'>Total</td>";
			$html_view .= "<td colspan='2'>".count($listado)."</td>";
			$html_view .= "</tr>";

		}

		$html_view .= "</table>";
		$html_view .= "</fieldset>";
		return $html_view;
	}

	public function dameListaDeCpu($id_cpu)
	{

		$lista_con_datos = null;

		$tipos = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Memoria", $tipos);

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
		$html_view .= "<h4>Memorias</h4>";
		$html_view .= "<table class='table table-condensed' id='tabla_productos_cpu'>";
		$html_view .= "<tr>";
		$html_view .= "<th>Marca</th>
					   <th>Tipo</th>
					   <th>Capacidad</th>
					   <th>Velocidad (Mhz)</th>";
		$html_view .= "</tr>";

		if ($listado == null)
		{
			$html_view .= "<tr>";
			$html_view .= "<td colspan='4'>No tiene memorias</td>";
			$html_view .= "</tr>";
		}
		else
		{

			$capacidadUsada  = 0;
			$capacidadActual = 0;

			foreach ($listado as $fila => $contenido)
			{
				$html_view .= "<tr>";

				$datos_desc = Memoria_desc::dameDatos($contenido['id_memoria_desc']);

				$html_view .= "<td>".$datos_desc['marca']."</td>";
				$html_view .= "<td>".$datos_desc['tipo']."</td>";
				$html_view .= "<td>".$contenido['capacidad']." ".$contenido['unidad']."</td>";
				$capacidadActual = self::getCapacidadEnMegas($contenido['id_memoria']);
				$capacidadUsada += $capacidadActual;
				$html_view .= "<td>".$datos_desc['velocidad']."</td>";

				$html_view .= "</tr>";
			}
			$html_view .= "<tr id='total'>";
			$html_view .= "<td colspan='1'>Total</td>";
			$html_view .= "<td colspan='1'>".count($listado)."</td>";
			if (($capacidadUsada / 1024) < 1)
			{
				$html_view .= "<td colspan='2'>Usados  ".($capacidadUsada)." MB</td>";
			}
			else
			{
				$html_view .= "<td colspan='2'>Usados  ".($capacidadUsada / 1024)." GB</td>";
			}

			$html_view .= "</tr>";

		}

		$html_view .= "</table>";
		$html_view .= "</fieldset>";
		return $html_view;
	}

	public function agregar_marca_y_modelo($datos)
	{
		$id_marca = $datos['id_marca'];
		$modelo   = $datos['modelo'];
		$slots    = $datos['slots'];
		$mem_max  = $datos['mem_max'];

		if (BDD::getInstance()->query("INSERT INTO system.".self::claseMinus()." (id_marca,modelo,slots,mem_max) VALUES('$id_marca','$modelo','$slots','$mem_max') ")->get_error())
		{
			var_dump(BDD::getInstance());
			return "false";
		}
		else
		{
			return "true";
		}
	}

	public function getCapacidad($id)
	{
		$id_capacidad = BDD::getInstance()->query("select id_capacidad from system.memorias where id_memoria='$id' ")->_fetchRow()['id_capacidad'];
		return Capacidades::getNombre($id_capacidad);
	}

	public function getCapacidadEnMegas($id)
	{
		$fila = BDD::getInstance()->query("select id_capacidad,id_unidad from system.memorias where id_memoria='$id' ")->_fetchRow();
		$id_capacidad = $fila['id_capacidad'];
		$capacidad = Capacidades::getNombre($id_capacidad);
		$id_unidad = $fila['id_unidad'];
		$exponente = BDD::getInstance()->query("select potencia from system.unidades where id_unidad='$id_unidad' ")->_fetchRow()['potencia'];
		$capacidad = $capacidad * pow(1024, $exponente);
		return $capacidad;
	}

	public function liberar($id)
	{
		$id_vinculo = BDD::getInstance()->query("select id_vinculo from system.memorias where id_memoria='$id' ")->_fetchRow()['id_vinculo'];
		$inst_vinc  = new Vinculos();
		echo $inst_vinc->liberar($id_vinculo);
	}

	public function eliminarLogico($datos)
	{
		$id       = $datos['id_memoria'];
		$tipos    = Tipo_productos::get_rel_campos();
		$id_tipo_producto = array_search("Memoria", $tipos);
		$tabla    = "system.memorias";
		$campo_pk = "id_memoria";

		if ( ! BDD::getInstance()->query("SELECT system.baja_logica_producto('$id','$id_tipo_producto','$tabla','$campo_pk')")->get_error())
		{
			return 1;
		}
		else
		{
			var_dump(BDD::getInstance());return 0;}

	}

	/*
	Quizas hay codigo de arriba que pueda reutilizar, preguntar a rodri
	*/
		public function listarDisponiblesPara($computadora)			
	{			
		$cantQuePuedoAgregar = Computadoras::cantidadMemoriaLibre($computadora);
		var_dump($cantQuePuedoAgregar);
		if(Computadoras::tieneSlotsLibres($computadora) && $cantQuePuedoAgregar > 0){

			$capacidad = Capacidades::getMegasEnCapacidad($cantQuePuedoAgregar);


			//Consigo las memorias que hay en stock que tengan menos o igual memoria de la que puedo agregar
			$datos = BDD::getInstance()->query("SELECT *,'<a id=\"agregar_memoria\" class=\"pointer_mon\"id_memoria=\"' || id_memoria || '\"><i class=\"green large plus outline icon\" title=\"Agregar memoria (Aperecen las memorias disponibles en stock) \"></i></a>' as action FROM system.memorias WHERE
 			EXISTS (SELECT id_pk_producto FROM system.vinculos WHERE id_tipo_producto =2) AND estado = '1' AND id_capacidad <= $capacidad")->_fetchAll();
			var_dump($datos);
			return self::generarListadoDeCpu($datos);
		}		
	}
	
	

	
}
?>