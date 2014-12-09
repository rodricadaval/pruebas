<?php

class Tipos_Computadoras {

	public function dameSelect_clase($clase = "", $sos = "") {

		$array_de_tipos = array("E", "N", "A", "S");

		$html_view = "<select id='select_clase" . $sos . "' name='clase'>";

		for ($i = 0; $i < count($array_de_tipos); $i++) {

			if ($array_de_tipos[$i] != "-") {
				if ($array_de_tipos[$i] == $clase) {
					$html_view .= "<option selected='selected' value=" . $array_de_tipos[$i] . ">" . $array_de_tipos[$i] . "</option>";
				} else {
					$html_view .= "<option value=" . $array_de_tipos[$i] . ">" . $array_de_tipos[$i] . "</option>";
				}
			}
		}

		$html_view = $html_view . "</select>";
		return $html_view;
	}

	public function dameSelect_button_radio_clase($clase = "", $sos = "") {

		$array_de_tipos = array("E", "N", "A", "S");

		$html_view = "<td id='boton_radio'><label>";

		foreach ($array_de_tipos as $algo => $key) {
			switch ($key) {
				case 'E':
					if($key == $clase){
						$html_view .= "<input type='radio' name='clase' value=".$key." checked>PC de Escritorio";	
					}
					else{
						$html_view .= "<input type='radio' name='clase' value=".$key.">PC de Escritorio";		
					}
					$html_view.="<br>";
					break;
				case 'N':
					if($key == $clase){
						$html_view .= "<input type='radio' name='clase' value=".$key." checked>Notebook";	
					}
					else{
						$html_view .= "<input type='radio' name='clase' value=".$key.">Notebook";		
					}
					$html_view.="<br>";
					break;
				case 'A':
					if($key == $clase){
						$html_view .= "<input type='radio' name='clase' value=".$key." checked>All in one";	
					}
					else{
						$html_view .= "<input type='radio' name='clase' value=".$key.">All in one";		
					}
					$html_view.="<br>";
					break;
				case 'S':
					if($key == $clase){
						$html_view .= "<input type='radio' name='clase' value=".$key." checked>Servidor";	
					}
					else{
						$html_view .= "<input type='radio' name='clase' value=".$key.">Servidor";		
					}
					$html_view.="<br>";
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

/*

1/2 porcion de mila de pollo napo c fritas 50
1/2 porcion mila ternera napo con fritas 50
chebuzan de mila de pollo full 55
1/2 porcion mila ternera napo 40

 */
?>