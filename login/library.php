<?php
session_start();
$
$conn = pg_connect("host=localhost dbname=stock user=postgres password=123456789!") or die ("Oops! Server not connected"); // Connect to the host
pg_select($conn,"system.usuarios", $array) or die ("Oops! DB not connected"); // select the database
?>