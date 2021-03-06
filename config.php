<?php
/*
Creating constants for heavily used paths makes things a lot easier.
ex. require_once(LIBRARY_PATH . "Paginator.php")
defined("LIBRARY_PATH")
or define("LOG_PATH", realpath(dirname(__FILE__) . '/library'));
*/

defined("TEMPLATES") or define("TEMPLATES", realpath(dirname(__FILE__).'/templates'));

/*
Error reporting.
*/
ini_set("error_reporting", "true");
ini_set("session.cookie_lifetime","180");

error_reporting(E_ALL|E_STRCT);
?>