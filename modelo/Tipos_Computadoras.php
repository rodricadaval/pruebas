<?php

class Tipos_Computadoras {

	public function dameSelect_clase($clase = "", $sos = "")
	{

		$array_de_tipos = array("Escritorio" => "E", "Notebook" => "N", "All in One" => "A", "Servidor" => "S", "UltraBook" => "U");

		$html_view = "<select id='select_clase".$sos."' name='clase'>";

		foreach ($array_de_tipos as $campo => $valor)
		{
			if ($valor != "-")
			{
				if ($valor == $clase)
				{
					$html_view .= "<option selected='selected' value=".$valor.">".$campo."</option>";
				}
				else
				{
					$html_view .= "<option value=".$valor.">".$campo."</option>";
				}
			}
		}

		$html_view = $html_view."</select>";
		return $html_view;
	}

	public function get_rel_campos()
	{
		return array("Escritorio" => "E", "Notebook" => "N", "All in One" => "A", "Servidor" => "S", "UltraBook" => "U");
	}

	public function dameSelect_button_radio_clase($clase = "", $sos = "")
	{

		$array_de_tipos = array("E", "N", "A", "S", "U");

		$html_view = "<td id='boton_radio'><label>";

		foreach ($array_de_tipos as $algo => $key)
		{
			switch ($key)
			{
				case 'E':
					if ($key == $clase)
				{
						$html_view .= "<input type='radio' name='clase' value=".$key." checked>PC de Escritorio";
					}
				else
				{
						$html_view .= "<input type='radio' name='clase' value=".$key.">PC de Escritorio";
					}
					$html_view .= "<br>";
					break;
				case 'N':
					if ($key == $clase)
				{
						$html_view .= "<input type='radio' name='clase' value=".$key." checked>Notebook";
					}
				else
				{
						$html_view .= "<input type='radio' name='clase' value=".$key.">Notebook";
					}
					$html_view .= "<br>";
					break;
				case 'A':
					if ($key == $clase)
				{
						$html_view .= "<input type='radio' name='clase' value=".$key." checked>All in one";
					}
				else
				{
						$html_view .= "<input type='radio' name='clase' value=".$key.">All in one";
					}
					$html_view .= "<br>";
					break;
				case 'S':
					if ($key == $clase)
				{
						$html_view .= "<input type='radio' name='clase' value=".$key." checked>Servidor";
					}
				else
				{
						$html_view .= "<input type='radio' name='clase' value=".$key.">Servidor";
					}
					$html_view .= "<br>";
					break;

				case 'U':
					if ($key == $clase)
				{
						$html_view .= "<input type='radio' name='clase' value=".$key." checked>UltraBook";
					}
				else
				{
						$html_view .= "<input type='radio' name='clase' value=".$key.">UltraBook";
					}
					$html_view .= "<br>";
					break;
				default:
					# code...
					break;
			}
		}

		$html_view .= "</label></td>";

		return $html_view;
	}
}
?>