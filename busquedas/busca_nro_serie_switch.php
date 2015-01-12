<?php
require_once '../ini.php';

$sql = "select * from system.switchs where lower(num_serie) = lower('{$_POST['serie']}') and estado = 1 ";

if (BDD::getInstance()->query($sql)->get_count()) {
	echo "false";
} else {
	echo "true";
}

?>