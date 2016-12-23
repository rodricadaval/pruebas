<?php

/**
* 
*/
class Tablets
{
	
	public function getCompletoById($id)
	{
		return BDD::getInstance()->query("SELECT T.id_tablet,T.num_serie,T.id_sector,T.id_usuario,T.descripcion,T.estado,DESC.id_tablet_desc,DESC.modelo,DESC.procesador,DESC.memoria,DESC.disco,DESC.estado,M.nombre,M.id_marca,M.estado from system.tablets T INNER JOIN system.tablet_desc DESC ON DESC.id_tablet_desc = T.id_tablet_desc INNER JOIN system.marcas M ON M.id_marca = DESC.id_marca where id_tablet = '$id' ");
	}
}


?>