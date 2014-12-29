<?php 
	
	class Historial_acciones {

		public static function claseMinus() {
			return strtolower(get_class());
		}

		public function registrarMovimientos($datos){

			BDD::getInstance()->query("INSERT INTO system.". self::claseMinus() . " ");
		}

} ?>