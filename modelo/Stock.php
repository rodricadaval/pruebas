<?php

class Stock
{

    public static function claseMinus() 
    {
        return strtolower(get_class());
    }

    public function listarTodos() 
    {

        $parametros[] = array("insumo" => "-", "nro_serie" => "-", "nro_serie_pc" => "-", "marca" => "-", "modelo" => "-", "capacidad" => "-", "tipo" => "-", "area" => "-", "accion" => "-");

        $inst_table = BDD::getInstance()->query('select * from system.stock');

        $i = 0;
        while ($fila = $inst_table->_fetchRow()) {
            //$parametros[$i] = array("insumo" => "-", "nro_serie" => "-", "nro_serie_pc" => "-", "marca" => "-", "modelo" => "-", "capacidad" => "-", "tipo" => "-", "area" => "-", "accion" => "-");

            foreach ($fila as $campo => $valor) {
                if ($campo == "insumo") {
                    $parametros[$i] = array_merge($parametros[$i], Insumos::dameDatosDelInsumo($valor));
                } else if ($campo == "deposito") {
                    $parametros[$i][$campo] = Areas::getNombre($valor);
                } else {
                    $parametros[$i][$campo] = $valor;
                }
            }
            $i++;
        }
        echo json_encode($parametros);
    }
}
?>