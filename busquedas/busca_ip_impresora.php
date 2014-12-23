<?php
require_once '../ini.php';

$sql = "select * from system.impresoras where lower(ip) = lower('{$_POST['ip']}') and estado = 1 ";

if (BDD::getInstance()->query($sql)->get_count()) {
	echo "false";
} else {
	echo "true";
}

?>