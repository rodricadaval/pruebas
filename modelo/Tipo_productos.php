<?php

class Tipo_productos
{

    public static function claseMinus()
    {
        return strtolower(get_class());
    }

    public function _construct()
    {
    }

    public function listarTodos()
    {

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
        echo json_encode($data);
    }

    public function getNombre($id)
    {
        $fila = BDD::getInstance()->query("select nombre from system.". self::claseMinus()." where id_tipo_producto = '$id' ")->_fetchRow();

        return $fila;
    }

    public function dameDatos($id)
    {
        $fila = BDD::getInstance()->query("select * from system.". self::claseMinus()." where id_tipo_producto = '$id' ")->_fetchRow();

        return $fila;
    }

    public function get_rel_campos()
    {

        $bdd   = strtolower(get_class());
        $tabla = BDD::getInstance()->query("select * from system.". self::claseMinus());
        $array = array();

        while ($fila = $tabla->_fetchRow())
        {

            $array[$fila['id_tipo_producto']] = $fila['nombre'];

        }
        return $array;
    }
}
?>