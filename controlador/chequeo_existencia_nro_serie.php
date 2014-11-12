<?php
require_once '../ini.php';
if (isset($_POST['nro_serie'])) {
	echo Monitores::no_existe($_POST['nro_serie']);
} else {
	echo 0;
}
?>