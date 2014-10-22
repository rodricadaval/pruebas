<?php 
class BDD{

	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_count = 0;
			
	public function __construct () {
		try {
			$this->_pdo = new PDO('pgsql:host=localhost;dbname=stock;user=postgres;password=123456789!');
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}
	
	public static function getInstance () {
		if (!isset(self::$_instance)){
			self::$_instance = new BDD();
		}
		return self::$_instance;
	}

	public function query ($sql , $params = array()) {
		$this->_error = false;
		
		if ($this->_query = $this->_pdo->prepare($sql)){
			$i = 1;
			if (count($params)) {
				foreach ($params as $param) {
					$this->_query->bindValue($i , $param );
					$i++;
				}
			}
			
			if ($this->_query->execute()){
				$this->_count = $this->_query->rowCount();
				//$this->_results = $this->_query->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$this->_results = null;
				$this->_error = $this->_pdo->errorInfo();
			}
		} else {
			$this->_error = $this->_pdo->errorInfo();
		}
		return $this;
	}

	public function get_results(){
		return $this->_query;
	}
	public function _fetchAll(){
		return $this->_query->fetchAll(PDO::FETCH_ASSOC);
	}
	public function _fetchRow(){
		return $this->_query->fetch(PDO::FETCH_ASSOC);
	}
	public function get_count(){
		return $this->_count;
	}
	public function get_error()
	{
		return $this->_error;
	}
}
 ?>