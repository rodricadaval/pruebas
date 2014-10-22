<?php 

class Pedidos{

	public function listarTodos(){
		
		$inst_table = BDD::getInstance()->query('select * from system.pedidos');
		$i=0;
		while($fila = $inst_table->_fetchRow()){
			foreach($fila as $campo => $valor){
				$data[$i][$campo] = $valor;
			}
		$i++;
		}
		echo json_encode($data);
	}
} 
?>