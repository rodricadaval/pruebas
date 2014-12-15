<?php

class Velocidades {

	public function dameSelect_velocidades($clase = "", $sos = "") {

		$array_de_velocidades = array(800,1066,1333,1600,1866,2133,2400,2666);

		$html_view = "<select id='select_clase" . $sos . "' name='clase'>";

		for ($i = 0; $i < count($array_de_velocidades); $i++) {

			if ($array_de_velocidades[$i] != "-") {
				if ($array_de_velocidades[$i] == $clase) {
					$html_view .= "<option selected='selected' value=" . $array_de_velocidades[$i] . ">" . $array_de_velocidades[$i] . " Mhz</option>";
				} else {
					$html_view .= "<option value=" . $array_de_velocidades[$i] . ">" . $array_de_velocidades[$i] . " Mhz</option>";
				}
			}
		}

		$html_view = $html_view . "</select>";
		return $html_view;
	}
}
?>