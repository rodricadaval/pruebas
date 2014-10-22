<?php

require_once 'ini.php';

if (isset ($_POST) && count ($_POST)) {
	
	$clase 	= array_shift ($_POST);
	$metodo = array_shift ($_POST);
	
	$inst = new $clase();
	return $inst->$metodo(array_values ($_POST));
}
else{echo "no entro";}
?>