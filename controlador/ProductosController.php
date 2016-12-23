<?php
require_once "../ini.php";

if ( ! isset($_POST['action']))
{
	$url = array("vista/productos/view_agregar_producto.php");
	$parametros = array("TABLA" => "Agregar Producto", "TITULO" => "Agregar");
	echo Disenio::HTML($url, $parametros);
}
else
{

	$inst_marcas = new Marcas();

	switch ($_POST['action'])
	{
		case 'agregar_monitor':
		if ( ! isset($_POST['tipo']))
		{

			$url           = array("vista/monitor/view_agregar_monitor.php");
			$select_marcas = $inst_marcas->dameSelect("monitores");
			$titulo        = "Menu para agregar un Monitor";
			$parametros    = array("Producto"    => "Monitor", "select_marcas_monitores"    => $select_marcas, "titulo"    => $titulo);
			echo Disenio::HTML($url, $parametros);

		}
		elseif ($_POST['tipo'] == "sel_modelos")
		{

			$inst_mon_desc = new Monitor_desc();
			echo $inst_mon_desc->dameSelect($_POST['value'], "_Monitor");

		}
		break;

		case 'agregar_computadora':
		if ( ! isset($_POST['tipo']))
		{

			$url           = array("vista/computadora/view_agregar_computadora.php");
			$select_marcas = $inst_marcas->dameSelect("computadoras");
			$select_clases = Tipos_Computadoras::dameSelect_clase();
			$titulo        = "Menu para agregar una Computadora";
			$parametros    = array("Producto"    => "Computadora", "select_marcas_computadoras"    => $select_marcas, "select_clases_Computadora"    => $select_clases, "titulo"    => $titulo);
			echo Disenio::HTML($url, $parametros);

		}
		elseif ($_POST['tipo'] == "sel_modelos")
		{

			$inst_comp_desc = new Computadora_desc();
			echo $inst_comp_desc->dameSelect($_POST['value'], "_Computadora");
		}
		break;

		case 'agregar_memoria':
		if ( ! isset($_POST['tipo']))
		{

			$url                = array("vista/memoria/view_agregar_memoria.php");
			$select_marcas      = $inst_marcas->dameSelect("memorias");
			$select_tipos       = Memoria_desc::dameSelect("_memorias");
			$select_capacidades = Capacidades::dameSelect("", "_memorias");
			$select_unidades    = Unidades::dameSelect("", "_memorias");
			$titulo             = "Menu para agregar una Memoria Ram";
			$parametros         = array("Producto"         => "Memoria", "select_marcas_memorias"         => $select_marcas, "select_tipos_memorias"         => $select_tipos, "select_capacidades"         => $select_capacidades, "select_unidades"         => $select_unidades, "titulo"         => $titulo);
			echo Disenio::HTML($url, $parametros);

		}
		elseif ($_POST['tipo'] == "sel_velocidades")
		{

			$inst_vel = new Velocidades();
			echo $inst_vel->dameSelect($_POST['value_marca'], $_POST['value_tipo']);

		}
		elseif ($_POST['tipo'] == "sel_velocidades_nueva_marca")
		{
			$inst_vel = new Velocidades();
			echo $inst_vel->dameSelectVelocidadesPosiblesParaTipo($_POST['value_tipo']);
		}
		break;

		case 'agregar_memoria_a_computadora':			
		if ( ! isset($_POST['tipo']))
		{

			$url                = array("vista/memoria/view_agregar_memoria_a_computadora.php");
			$select_marcas      = $inst_marcas->dameSelect("memorias");
			$select_tipos       = Memoria_desc::dameSelect("_memorias");
			$select_capacidades = Capacidades::dameSelect("", "_memorias");
			$select_unidades    = Unidades::dameSelect("", "_memorias");
			$titulo             = "Menu para agregar una Memoria Ram";
			$parametros         = array("Producto"         => "Memoria", "select_marcas_memorias"         => $select_marcas, "select_tipos_memorias"         => $select_tipos, "select_capacidades"         => $select_capacidades, "select_unidades"         => $select_unidades, "titulo"         => $titulo);
			echo Disenio::HTML($url, $parametros);

		}
		elseif ($_POST['tipo'] == "sel_velocidades")
		{

			$inst_vel = new Velocidades();
			echo $inst_vel->dameSelect($_POST['value_marca'], $_POST['value_tipo']);

		}
		elseif ($_POST['tipo'] == "sel_velocidades_nueva_marca")
		{
			$inst_vel = new Velocidades();
			echo $inst_vel->dameSelectVelocidadesPosiblesParaTipo($_POST['value_tipo']);
		}
		break;

		case 'agregar_productos_a_computadora':
		$url        = array("vista/productos/view_agregar_productos_a_computadora.php");
		if(! isset($_POST['id_cpu'])){

		}else{
			$id_cpu     = $_POST['id_cpu'];	
		}
		
			//die("<pre>". json_encode($id_cpu,JSON_PRETTY_PRINT) . "</pre>");
		$parametros = array("id_cpu" => $id_cpu);
		echo Disenio::HTML($url, $parametros);
		break;

		case 'agregar_disco':

		$url                = array("vista/disco/view_agregar_disco.php");
		$select_marcas      = $inst_marcas->dameSelect("discos");
		$select_capacidades = Capacidades::dameSelect("", "_discos");
		$select_unidades    = Unidades::dameSelect("", "_discos");
		$titulo             = "Menu para agregar un Disco";
		$parametros         = array("Producto"         => "Disco", "select_marcas_discos"         => $select_marcas, "select_capacidades"         => $select_capacidades, "select_unidades"         => $select_unidades, "titulo"         => $titulo);
		echo Disenio::HTML($url, $parametros);

		break;

		case 'agregar_disco_a_computadora':

		$url                = array("vista/disco/view_agregar_disco_a_computadora.php");
		$select_marcas      = $inst_marcas->dameSelect("discos");
		$select_capacidades = Capacidades::dameSelect("", "_discos");
		$select_unidades    = Unidades::dameSelect("", "_discos");
		$titulo             = "Menu para agregar un Disco";
		$parametros         = array("Producto"         => "Disco", "select_marcas_discos"         => $select_marcas, "select_capacidades"         => $select_capacidades, "select_unidades"         => $select_unidades, "titulo"         => $titulo);
		echo Disenio::HTML($url, $parametros);

		break;

		case 'agregar_impresora':

		if ( ! isset($_POST['tipo']))
		{

			$url           = array("vista/impresora/view_agregar_impresora.php");
			$select_marcas = $inst_marcas->dameSelect("impresoras");
			$titulo        = "Menu para agregar una Impresora";
			$parametros    = array("Producto"    => "Impresora", "select_marcas_impresoras"    => $select_marcas, "titulo"    => $titulo);
			echo Disenio::HTML($url, $parametros);

		}
		elseif ($_POST['tipo'] == "sel_modelos")
		{

			$inst_imp_desc = new Impresora_desc();
			echo $inst_imp_desc->dameSelect($_POST['value'], "_Impresora");

		}

		break;

		case 'agregar_router':

		if ( ! isset($_POST['tipo']))
		{

			$url           = array("vista/router/view_agregar_router.php");
			$select_marcas = $inst_marcas->dameSelect("routers");
			$titulo        = "Menu para agregar un Router";
			$parametros    = array("Producto"    => "Router", "select_marcas_routers"    => $select_marcas, "titulo"    => $titulo);
			echo Disenio::HTML($url, $parametros);

		}
		elseif ($_POST['tipo'] == "sel_modelos")
		{

			$inst_rout_desc = new Router_desc();
			echo $inst_rout_desc->dameSelect($_POST['value'], "_Router");

		}

		break;

		case 'agregar_switch':

		if ( ! isset($_POST['tipo']))
		{

			$url           = array("vista/switch/view_agregar_switch.php");
			$select_marcas = $inst_marcas->dameSelect("switchs");
			$titulo        = "Menu para agregar un Switch";
			$parametros    = array("Producto"    => "Switch", "select_marcas_switchs"    => $select_marcas, "titulo"    => $titulo);
			echo Disenio::HTML($url, $parametros);

		}
		elseif ($_POST['tipo'] == "sel_modelos")
		{

			$inst_swi_desc = new Switch_desc();
			echo $inst_swi_desc->dameSelect($_POST['value'], "_Switch");

		}

		break;

		case 'asignar_disco_a_computadora':

		$id_cpu = array("id_cpu" => $_POST['id_cpu']);			
		$html_view = Discos::disponibles();
		
		$url = array("vista/disco/view_asignar_disco_a_computadora.php");
		$parametros = array("Discos" => $html_view,"id_cpu" => $id_cpu);
		echo Disenio::HTML($url, $parametros);
		break;

		case 'asignar_memoria_a_computadora':
			//Tengo en el post el id, se me dificultaba conseguirlo desde este php porque
			//no tengo acceso al TAB de memorias y disco de la view

		$id_cpu = array("id_cpu" => $_POST['id_cpu']);

		$html_view = Memorias::listarDisponiblesPara($id_cpu);
			//die("<pre>". json_encode($html_view,JSON_PRETTY_PRINT) . "</pre>");
		$url = array("vista/memoria/view_asignar_memoria_a_computadora.php");
		$parametros = array("Memorias" => $html_view,"id_cpu" => $id_cpu);
		echo Disenio::HTML($url, $parametros);
		break;

		case 'asignar_monitor_a_computadora':
		$id_cpu = array("id_cpu" => $_POST['id_cpu']);
		$html_view = Monitores::disponibles();
			//die("<pre>". json_encode($html_view,JSON_PRETTY_PRINT) . "</pre>");
		$url = array("vista/monitor/view_asignar_monitor_a_computadora.php");
		$parametros = array("Monitores" => $html_view,"id_cpu" => $id_cpu);
		echo Disenio::HTML($url, $parametros);
		break;
		
		
		default:
			# code...
		break;

	}
}
