<?php
/**
 * PHP version 7.0
 * 
 * @category Desciption
 * @package  Category
 * @author   Name <danielguerrero94@gmail.com>
 * @license  http://url.com MIT
 * @link     http://url.com
 */
require_once "../ini.php";

$parametros = array();

if (isset($_POST['action'])) {

    $inst_areas = new Areas();
    $action     = $_POST['action'];
    unset($_POST['action']);

    switch ($action)
    {
    case 'crear':

        foreach ($_POST as $clave => $valor) {
            $parametros[$clave] = $valor;
        }
        echo $inst_areas->crearArea($parametros);

        break;

    case 'modificar':

        foreach ($_POST as $clave => $valor) {
            $parametros[$clave] = $valor;
        }

        echo $inst_areas->modificarDatos($parametros);

        break;

    case 'eliminar':

        echo $inst_areas->eliminar($_POST['id_area']);
        break;

    default:
        // code...
        break;
    }
} else {

    $archivos   = array("vista/area/view_areas.php");
    $parametros = array("TABLA" => "Areas", "");
    echo Disenio::HTML($archivos, $parametros);
}
?>