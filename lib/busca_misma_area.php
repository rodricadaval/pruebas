<?php
require_once '../ini.php';

$sql = "select id_vinculo from system.computadoras where lower(num_serie) = lower('{$_POST['cpu_serie']}') and estado = 1 ";

if (strtolower($_POST['cpu_serie']) != "sin serie") {
	if (BDD::getInstance()->query($sql)->get_count()) {
		$id = BDD::getInstance()->query($sql)->_fetchRow()['id_vinculo'];
		echo Vinculos::mismaArea($id, $_POST['id_area']);
	} else {
		echo "false";
	}
} else {echo "true";}

?>