<?php

/**
* 
*/
class Tablets
{
	
	public function getDataMemorandumById($id)
	{
		$ret = BDD::getInstance()->query("SELECT num_serie,M.nombre as \"marca\",modelo,A.nombre as \"sector\",U.nombre_apellido,D.procesador,D.memoria,D.disco from system.tablets T 
INNER JOIN system.tablet_desc D ON D.id_tablet_desc = T.id_tablet_desc 
INNER JOIN system.marcas M ON M.id_marca = D.id_marca 
INNER JOIN system.areas A ON A.id_area = T.id_sector 
INNER JOIN system.usuarios U ON U.id_usuario = T.id_usuario 
WHERE T.estado = 1 AND T.id_tablet = '$id'")->_fetchRow();
		return $ret; 
	}

	public function listarCorrecto($datos_extra = "")
	{

		$data = null;

		$inst_table = BDD::getInstance()->query("
			SELECT num_serie,M.nombre as \"marca\",modelo,A.nombre as \"sector\",U.nombre_apellido,descripcion,
			'<a id=\"modificar_usuario_tablet\" class=\"pointer_tablet\"id_tablet=\"' || id_tablet || '\">
			<i class=\"purple large user icon\" title=\"Asignar un Usuario\"></i>
			</a>
			<a id=\"ver_detalle\" class=\"pointer_tablet\"id_tablet=\"' || id_tablet || '\">
			<i class=\"orange large browser icon\" title=\"Ver sus componentes\"></i>
			</a>
			<a id=\"agregar_descripcion_tablet\" class=\"pointer_tablet\"id_tablet=\"' || id_tablet || '\">
			<i class=\"blue large book icon\" title=\"Ver o editar descripcion\"></i>
			</a>
			<a id=\"desasignar_usuario_tablet\" class=\"pointer_tablet\"id_tablet=\"' || id_tablet || '\">
			<i class=\"large minus outline icon\" title=\"Liberar Tablet\"></i>
			</a>
			<a id=\"generar_memorandum\" class=\"pointer_tablet\"id_tablet=\"' || id_tablet || '\">
			<i title=\"Generar Memorandum\" class=\"orange file pdf outline large icon\"></i>
			</a>
			<a id=\"cambiar_num_serie\" class=\"pointer_tablet\"id_tablet=\"' || id_tablet || '\">
			<i class=\"purple large edit icon\" title=\"Cambiar num de serie\"></i>
			</a>
			<a id=\"eliminar_tablet\" class=\"pointer_tablet\"id_tablet=\"' || id_tablet || '\">
			<i class=\"red large trash icon\" title=\"Dar de baja\"></i>
			</a>'
			as m from system.tablets T INNER JOIN system.tablet_desc D ON D.id_tablet_desc = T.id_tablet_desc INNER JOIN system.marcas M ON M.id_marca = D.id_marca INNER JOIN system.areas A ON A.id_area = T.id_sector INNER JOIN system.usuarios U ON U.id_usuario = T.id_usuario WHERE T.estado = 1");

		$todo  = $inst_table->_fetchAll();

		if ($datos_extra[0] == "json")
		{
			echo json_encode($todo);
		}
		else
		{
			return $todo;
		}
	}

	public function getDescripcion($id)
	{
		$ret = BDD::getInstance()->query("SELECT descripcion FROM system.tablets WHERE id_tablet = '$id'")->_fetchRow()['descripcion'];
		return $ret;
	}

	public function setDescripcion($id,$descripcion)
	{
		if ($descripcion != "") {
			return !BDD::getInstance()->query("UPDATE system.tablets SET descripcion = '$descripcion' WHERE id_tablet = '$id'")->get_error();
		}else {
			return 0;
		}
	}

	public function getNumSerie($id)
	{
		return BDD::getInstance()->query("SELECT num_serie FROM system.tablets WHERE id_tablet = '$id'")->_fetchRow()['num_serie'];
	}

	public function setNumSerie($id,$num_serie)	
	{

		if ($num_serie != "")	 {
			return !BDD::getInstance()->query("UPDATE system.tablets SET num_serie = '$num_serie' WHERE id_tablet = '$id'")->get_error();
		}
		else {
			return 0;
		}
	}

	public function getComponentes($id)
	{
		return BDD::getInstance()->query("SELECT D.procesador,D.memoria,D.disco FROM system.tablets T INNER JOIN system.tablet_desc D ON D.id_tablet_desc = T.id_tablet_desc WHERE T.id_tablet = '$id'")->_fetchRow();
	}

	public function darDeBaja($id)
	{
		return !BDD::getInstance()->query("UPDATE system.tablets SET estado = 0 WHERE id_tablet = '$id'")->get_error();
	}

	public function getUsuario($id)
	{
		return BDD::getInstance()->query("SELECT T.id_usuario,A.nombre as \"sector\",U.nombre_apellido FROM system.tablets T INNER JOIN system.usuarios U ON U.id_usuario = T.id_usuario INNER JOIN system.areas A ON A.id_area = U.area WHERE T.id_tablet = '$id'")->_fetchRow();
	}

	public function setUsuario($id_tablet,$nombre_usuario)
	{
		$inst = new Usuarios();
		$id_usuario = $inst->getIdByNombre($nombre_usuario);
		$id_sector = $inst->dame_id_area($id_usuario);
		return !BDD::getInstance()->query("UPDATE system.tablets SET id_usuario = '$id_usuario',id_sector = '$id_sector' WHERE id_tablet = '$id_tablet'")->get_error();
	}

	public function setLibre($id)
	{
		return !BDD::getInstance()->query("UPDATE system.tablets SET id_sector = 1,id_usuario = 1 WHERE id_tablet = '$id'")->get_error();
	}

}
?>