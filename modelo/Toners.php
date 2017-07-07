<?php

class Toners
{
    
    public function getDataMemorandumById($id)
    {
        $ret = BDD::getInstance()->query(
            "SELECT concat(m.nombre,' ',i.modelo) AS impresora,a.nombre AS area,t.cantidad FROM system.toners t 
			JOIN system.impresora_desc i ON i.id_impresora_desc = t.id_impresora_desc
			JOIN system.areas a ON a.id_area = t.id_area
			JOIN system.marcas m ON m.id_marca = i.id_marca
			WHERE a.estado = 1 AND i.estado = 1 AND m.estado = 1 AND t.estado = 1 AND t.id_toner = '$id'"
        )->_fetchRow();
        return $ret; 
    }

    public function listarCorrecto($datos_extra = "")
    {
        //Se podria tambien agregar como accion cambiar el modelo de impresora pero es poco probable asi que no lo priorizo
        $acciones = "'<a id=\"ver_area\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\">
		<i class=\"green large edit icon\" title=\"Asignar Area\"></i></a>
		<a id=\"ver_liberar\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\"><i class=\"blue large minus icon\" title=\"Liberar\"></i></a>		
		<a id=\"ver_descripcion\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\"><i class=\"blue large book icon\" title=\"Ver o editar descripcion\"></i></a>		
		<a id=\"generar_memorandum\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\"><i title=\"Generar Memorandum\" class=\"orange file pdf outline large icon\"></i></a>
		<a id=\"ver_baja\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\"><i class=\"red large trash icon\" title=\"Dar de baja\"></i></a>' as m";

        $query = "SELECT concat(m.nombre,' ',i.modelo) AS impresora,a.nombre AS area,t.cantidad,t.descripcion,t.fecha,".$acciones." FROM system.toners t 
		JOIN system.impresora_desc i ON i.id_impresora_desc = t.id_impresora_desc
		JOIN system.marcas m ON m.id_marca = i.id_marca
		JOIN system.areas a ON a.id_area = t.id_area
		WHERE a.estado = 1 AND i.estado = 1 AND m.estado = 1 AND t.estado = 1
		ORDER BY t.fecha DESC";

        $ret = BDD::getInstance()->query($query)->_fetchAll();
        if ($datos_extra[0] == "json") {
            echo json_encode($ret);
        }
        else
        {
            return $todo;
        }
    }

    public function listarEnStock($datos_extra = "")
    {
        //Se podria tambien agregar como accion cambiar el modelo de impresora pero es poco probable asi que no lo priorizo
        $acciones = "'<a id=\"ver_area\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\">
		<i class=\"green large edit icon\" title=\"Asignar Area\"></i></a>
		<a id=\"ver_liberar\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\"><i class=\"blue large minus icon\" title=\"Liberar\"></i></a>		
		<a id=\"ver_descripcion\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\"><i class=\"blue large book icon\" title=\"Ver o editar descripcion\"></i></a>		
		<a id=\"generar_memorandum\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\"><i title=\"Generar Memorandum\" class=\"orange file pdf outline large icon\"></i></a>
		<a id=\"ver_baja\" class=\"pointer_toner\"id_toner=\"' || t.id_toner || '\"><i class=\"red large trash icon\" title=\"Dar de baja\"></i></a>' as m";

        $query = "SELECT concat(m.nombre,' ',i.modelo) AS impresora,a.nombre AS area,t.cantidad,t.descripcion,t.fecha,".$acciones." FROM system.toners t 
		JOIN system.impresora_desc i ON i.id_impresora_desc = t.id_impresora_desc
		JOIN system.marcas m ON m.id_marca = i.id_marca
		JOIN system.areas a ON a.id_area = t.id_area
		WHERE a.estado = 1 AND i.estado = 1 AND m.estado = 1 AND t.estado = 1 and a.id_area = 1
		ORDER BY t.fecha DESC";

        $ret = BDD::getInstance()->query($query)->_fetchAll();
        if ($datos_extra[0] == "json") {
            echo json_encode($ret);
        }
        else
        {
            return $todo;
        }
    }

    /*Para el area y para la impresora voy a cambiar el disenio y voy a mandar el id como parte de la consulta para despues 
    no tener que buscar por string cual es su id*/

    public function getArea($id)
    {
        return BDD::getInstance()->query(
            "SELECT t.id_area FROM system.toners t
			WHERE t.id_toner = '$id'"
        )->_fetchRow()['id_area'];
    }

    public function setArea($id_toner,$id_area)
    {
        return !BDD::getInstance()->query("UPDATE system.toners SET id_area = '$id_area' WHERE id_toner = '$id_toner'")->get_error();
    }

    public function getImpresora($id_toner)
    {
        return BDD::getInstance()->query(
            "SELECT  as impresora FROM system.toners t
			JOIN system.impresora_desc i ON i.id_impresora_desc = t.id_impresora_desc
			JOIN system.marcas m ON m.id_marca = i.id_marca
			WHERE t.id_toner = '$id'"
        )->_fetchRow()['impresora'];
    }

    public function setImpresora($id_toner,$id_impresora_desc)
    {
        return !BDD::getInstance()->query("UPDATE system.toners SET id_impresora_desc = '$id_impresora_desc' WHERE id_toner = '$id'")->get_error();    
    }

    public function getDescripcion($id)
    {
        return BDD::getInstance()->query("SELECT descripcion FROM system.toners WHERE id_toner = '$id'")->_fetchRow()['descripcion'];
    }

    public function setDescripcion($id,$descripcion)
    {
        return !BDD::getInstance()->query("UPDATE system.toners SET descripcion = '$descripcion' WHERE id_toner = '$id'")->get_error();
    }

    public function setLibre($id)
    {
        return !BDD::getInstance()->query("UPDATE system.toners SET id_area = 1 WHERE id_toner = '$id'")->get_error();
    }

    public function darDeBaja($id)
    {
        return !BDD::getInstance()->query("UPDATE system.toners SET estado = 0 WHERE id_toner = '$id'")->get_error();
    }

    public function darDeAlta($id_impresora_desc,$id_area,$cantidad)
    {
        return !BDD::getInstance()->query("INSERT INTO system.toners (id_impresora_desc,id_area,cantidad) values ($id_impresora_desc,$id_area,$cantidad)")->get_error();    
    }
}
?>