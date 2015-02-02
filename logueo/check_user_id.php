<?php
session_start();

include '../ini.php'; // include the library for database connection

$inst_usuario = new Usuarios();
$nombre = $inst_usuario->getNombreDePila($_SESSION['userid']);

echo $nombre;

?>