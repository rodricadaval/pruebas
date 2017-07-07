<?php
class Disenio
{

    private static $_template = '';
    private $_db, $_menu = '';

    public function __construct()
    {
        $this->_db = BDD::getInstance();
    }

    public static function HTML($html = array(), $array = array())
    {

        foreach ($html as $archivo)
        {
            self::$_template .= file_get_contents("../".$archivo);
        }

        foreach ($array as $clave => $valor)
        {
            self::$_template = str_replace('{'.$clave.'}', $valor, self::$_template);
        }

        return self::$_template;
    }
}
?>