<?php

class Permisos
{

    public static function claseMinus()
    {
        return strtolower(get_class());
    }

    public function listarTodos()
    {
        //$inst_table = BDD::getInstance()->query("select *, '<a id=\"modificar_permiso\" class=\"pointer_mon\"tipo_acceso=\"' || tipo_acceso || '\"><i class=\"circular inverted green small edit icon\" title=\"Modificar Nombre \"></i></a>' as m from system." . self::claseMinus());
        $inst_table = BDD::getInstance()->query("select * from system.". self::claseMinus());
        $i = 0;
        while ($fila = $inst_table->_fetchRow())
        {
            foreach ($fila as $campo => $valor)
            {
                $data[$i][$campo] = $valor;
            }
            $i++;
        }

        echo (json_encode($data));
    }

    public function dameSelect($id)
    {
        $table     = BDD::getInstance()->query("select nombre, tipo_acceso from system.". self::claseMinus());
        $html_view = "<select id='select_permisos' name='permisos'>";

        while ($fila_permiso = $table->_fetchRow())
        {

            if ($fila_permiso['tipo_acceso'] == $id) {
                $html_view = $html_view."<option selected='selected' value=".$fila_permiso['tipo_acceso'].">".$fila_permiso['nombre']."</option>";
                $fila_area = $table->_fetchRow();
            }
            else
            {
                $html_view = $html_view."<option value=".$fila_permiso['tipo_acceso'].">".$fila_permiso['nombre']."</option>";
            }
        }
        return $html_view;
    }

    public function getNombre($id)
    {
        return $inst_table = BDD::getInstance()->query("select nombre from system.". self::claseMinus()." where tipo_acceso = '$id' ")->_fetchRow()['nombre'];
    }

    public function get_rel_campos()
    {
        $tabla = BDD::getInstance()->query("select * from system.". self::claseMinus());
        $array = array();

        while ($fila = $tabla->_fetchRow())
        {

            $array[$fila['tipo_acceso']] = $fila['nombre'];

        }
        return $array;
    }
}
?>