<?php
spl_autoload_register(function($clase){
	require_once 'modelo/' . $clase . '.php';
});

session_start();
?>