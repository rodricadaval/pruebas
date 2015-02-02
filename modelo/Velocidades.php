<?php

class Velocidades {

	public function dameSelect($id_marca = "", $tipo = "", $valor = "")
	{

		//$array_de_velocidades = array(800,1066,1333,1600,1866,2133,2400,2666);
		if ($id_marca != "" && $tipo != "")
		{
			$array_posta = BDD::getInstance()->query("SELECT distinct velocidad from system.memoria_desc where tipo = '$tipo' AND id_marca = '$id_marca' ");
		}
		else
		{
			$array_posta = BDD::getInstance()->query("SELECT distinct velocidad from system.memoria_desc");
		}

		$array_de_velocidades = $array_posta->_fetchAll();

		$html_view = "<select id='select_velocidades' name='velocidad'>";
		if (count($array_de_velocidades) == 0)
		{
			$html_view .= "<option value=''>No hay datos</option>";
		}

		for ($i = 0; $i < count($array_de_velocidades); $i++)
		{

			if ($array_de_velocidades[$i]['velocidad'] != "-")
			{
				if ($array_de_velocidades[$i]['velocidad'] == $valor)
				{
					$html_view .= "<option selected='selected' value=".$array_de_velocidades[$i]['velocidad'].">".$array_de_velocidades[$i]['velocidad']." Mhz</option>";
				}
				else
				{
					$html_view .= "<option value=".$array_de_velocidades[$i]['velocidad'].">".$array_de_velocidades[$i]['velocidad']." Mhz</option>";
				}
			}
		}

		$html_view = $html_view."</select>";
		return $html_view;
	}

	public function dameSelectDeMarca($marca = "", $clase = "", $sos = "")
	{

		//$array_de_velocidades = array(800,1066,1333,1600,1866,2133,2400,2666);
		$array_de_velocidades = BDD::getInstance()->query("SELECT distinct velocidad from system.memoria_desc where id_marca = '$marca' ")->_fetchAll();

		$html_view = "<select id='select_clase".$sos."' name='clase'>";

		for ($i = 0; $i < count($array_de_velocidades); $i++)
		{

			if ($array_de_velocidades[0][$i] != "-")
			{
				if ($array_de_velocidades[0][$i] == $clase)
				{
					$html_view .= "<option selected='selected' value=".$array_de_velocidades[0][$i].">".$array_de_velocidades[0][$i]." Mhz</option>";
				}
				else
				{
					$html_view .= "<option value=".$array_de_velocidades[0][$i].">".$array_de_velocidades[0][$i]." Mhz</option>";
				}
			}
		}

		$html_view = $html_view."</select>";
		return $html_view;
	}

	public function dameSelectVelocidadesPosiblesParaTipo($tipo = "")
	{
		if (isset($tipo))
		{
			if ($tipo == "DDR3")
			{
				$array_de_velocidades = array(1333, 1600, 1866, 2133, 2400, 2666);
			}
			else if ($tipo == "DDR2")
			{
				$array_de_velocidades = array(800, 1066);
			}

			$html_view = "<select id='select_velocidades_nueva_memoria' name='velocidad'>";

			for ($i = 0; $i < count($array_de_velocidades); $i++)
			{

				if ($array_de_velocidades[$i] != "-")
				{
					if ($array_de_velocidades[$i] == 1600)
					{
						$html_view .= "<option selected='selected' value=".$array_de_velocidades[$i].">".$array_de_velocidades[$i]." Mhz</option>";
					}
					else
					{
						$html_view .= "<option value=".$array_de_velocidades[$i].">".$array_de_velocidades[$i]." Mhz</option>";
					}
				}
			}

			$html_view = $html_view."</select>";
			return $html_view;
		}
	}
}
?>