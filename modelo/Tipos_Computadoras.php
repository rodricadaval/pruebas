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
}
/*

1/2 porcion de mila de pollo napo c fritas 50
1/2 porcion mila ternera napo con fritas 50
chebuzan de mila de pollo full 55
1/2 porcion mila ternera napo 40

 */
?>