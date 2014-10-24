<?php 
require_once "../ini.php";

if(isset($_POST['action']) && $_POST['action'] == "modificar"){
	$parametros = array();
	unset($_POST['action']);

	if(isset($_POST['password'])){
		if($_POST['password']==""){
		unset($_POST['password']);
		}
		else{
			$_POST['password']=$_POST['nueva_password'];
		}
		unset($_POST['nueva_password']);
		unset($_POST['conf_password']);
	}

	foreach($_POST as $clave => $valor){
		$parametros[$clave] = $valor;
	}

	$inst_usuarios = new Usuarios();
	echo $inst_usuarios->modificarDatos($parametros);
}

else{
	$archivos = array("vista/view_usuarios.php");
	$parametros = array("TABLA" => "Usuarios","");
	echo Disenio::HTML($archivos,$parametros);
}

?>