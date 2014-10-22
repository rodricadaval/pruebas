<?php 

class Usuarios{

	public function listarTodos()
	{
		$armar_tabla = BDD::getInstance()->query("select * , '<a href=\"#\" class=\"modificar\"id_usuario=\"' || id_usuario || '\">MODIFICAR</a>' as m from system.usuarios")->_fetchAll();
		$i=0;

		foreach ($armar_tabla as $fila) {
				
			foreach ($fila as $campo => $valor) {
				if($campo == "password"){
					$valor = base64_decode(base64_decode(base64_decode($valor)));
				}
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
/*
	while ($reg = pg_fetch_assoc($armar_tabla)) {

				for ($j = 0 ; $j < pg_num_fields ($armar_tabla) ; $j ++) {
					$data[$i][pg_field_name($armar_tabla , $j)] = $reg[pg_field_name($armar_tabla , $j)];
				}
				$i++;
			}
*/
	echo (json_encode ($data));
	}

	public function listarTodosGuest()
	{
		$armar_tabla = BDD::getInstance()->query("select * from system.usuarios")->_fetchAll();
		$i=0;

		foreach ($armar_tabla as $fila) {
				
			foreach ($fila as $campo => $valor) {
				$data[$i][$campo] = $valor;
			}
			$i++;
		}

		echo (json_encode ($data));
	}

	public function getById($id){
		$fila = BDD::getInstance()->query("select * from system.usuarios where id_usuario = '$id' ")->_fetchRow();
		$fila['password'] = base64_decode(base64_decode(base64_decode($fila['password'])));
		return $fila;
	}

	public function listarPorNombre($nombre){

		$armar_tabla = BDD::getInstance()->query("select * from system.usuarios where usuario = '$nombre'")->_fetchAll();
		$i=0;

		foreach ($armar_tabla as $fila) {
				
			foreach ($fila as $campo => $valor) {
				$data[$i][$campo] = $valor;
			}
			$i++;
		}
		echo (json_encode ($data));
	}

	public function obtenerUsuarioLogin($usuario,$pass){
		 return BDD::getInstance()->query("select * from system.usuarios where usuario = '$usuario' AND password = '$pass' ");
	}

	public function chequeoExistenciaUsuario($usuario){
		return BDD::getInstance()->query("select * from system.usuarios where usuario = '$usuario' ");
	}

	public function obtener($clave, $id){
		$clase = $clave."s";
		$inst = new $clase();
		return $inst->dameNombre($id);
	}

	public function modificarDatos($datos){

		$cadena = "";

		$id = $datos['id_usuario'];
		unset($datos['id_usuario']);

		$firstTime = true;
		foreach ($datos as $key => $value) {
			if($key == "password"){
				$value = base64_encode(base64_encode(base64_encode($value)));
			}
			if($firstTime){
				$cadena .= "$key = '$value'";
				$firstTime = false;
			}
			else{
				$cadena .= ", $key = '$value'";
			}
		}
		if(BDD::getInstance()->query("UPDATE system.usuarios SET $cadena WHERE id_usuario = '$id' ")->get_results()){
		return 1;}
		else{return 0;}
	}
} 
?>