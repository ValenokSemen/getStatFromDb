<?php

/**
* 
*/
class DB
{

	public $pdo;
	private $error;
	# @bool ,  Connected to the database
    private $bConnected = false;
    # @array, The parameters of the SQL query
    private $parameters;
    # @object, PDO statement object
    private $sQuery;

	static private $limit;
	static private $order;

	function __construct()
	{
		$this->connect();
	}

	function connect() {
		$options = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');

		if(!$this->pdo){
			$dsn = 'mysql:dbname=' .DATABASE_NAME . ';host=' . DATABASE_HOST . ';charset=utf8';
			$user = DATABASE_USER;
			$password = DATABASE_PASS;
			try {
				$this->pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_PERSISTENT => true));
				# Connection succeeded, set the boolean to true.
            	$this->bConnected = true;
				return true;
			} catch (PDOException $e) {
				$this->error = $e->getMessage();
				die($this->error);
				return false;
			}
		}else{
			$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			return true;
		}
	}

	private function Init($sql, $param = "")
	{
		if (!$this->bConnected) {
			$this->connect();
		}
		
		try {
			$this->parameters = $param;
			$this->sQuery     = $this->pdo->prepare($this->BuildParams($sql, $this->parameters));
				if (!empty($this->parameters)) {
					if (array_key_exists(0, $param)) {
						$parametersType = true;
						array_unshift($this->parameters, "");
						unset($this->parameters[0]);
					} else {
						$parametersType = false;
					}
					foreach ($this->parameters as $column => $value) {
						$this->sQuery->bindParam($parametersType ? intval($column) : ":" . $column, $this->parameters[$column]);
					}
				}

				# Execute SQL 
           		$this->sQuery->execute();
				
			} catch (PDOException $e) {
				# Write into log and display Exception
				die();
			}

		$this->parameters = array();
		
	}

	private function BuildParams($sql, $params = null)
	{
		if (!empty($params)) {
			$rawStatement = explode(" ", $sql);
			foreach ($rawStatement as $value) {
				if (strtolower($value) == 'in') {
					return str_replace("(?)", "(" . implode(",", array_fill(0, count($params), "?")) . ")", $sql);
				}
			}
		}

		return $sql;
	}

	public function query($sql, $params = null, $tableSchema = null)
	{
		$sql = $sql . self::extra();
		$sql = trim(str_replace("\r", " ", $sql));
		$rawStatement = explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $sql));
		$this->Init($sql, $params);
		$statement = strtolower($rawStatement[0]);

		if ($statement === 'select' || $statement === 'show') {
			return $this->sQuery->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, get_called_class(), array_keys($tableSchema));
		} elseif ($statement == 'insert' || $statement === 'update' || $statement == 'delete'){
			return $this->sQuery->rowCount();			
		} else {
			return null;
		}
	}

	public function column($sql, $params = null)
	{
		$this->Init($sql, $params);
		$resultColumn = $this->sQuery->fetchAll(PDO::FETCH_COLUMN);
		$this->sQuery->closeCursor();
		return $resultColumn;
	}

	/**
    * Starts the transaction
    * @return boolean, true on success or false on failure
    */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }


    /**
    *  Execute Transaction
    *  @return boolean, true on success or false on failure
    */
    public function executeTransaction()
    {
        return $this->pdo->commit();
    }
	
    static private function extra(){
			$extra = '';
			if(!empty(self::$order)) $extra .= ' '.self::$order;
			if(!empty(self::$limit)) $extra .= ' '.self::$limit;
			// cleanup
			self::$order = null;
			self::$limit = null;
			return $extra;
	}

	/**
	* MySQL limit method
	*/

    public function limit($limit){
			self::$limit = 'LIMIT '.$limit;
			return $this;
	}

	/**
	* MySQL Order By method
	*/

	public function order_by($by, $order_type='DESC')
	{
		$order = self::$order;
		if (is_array($by)) {
			foreach ($by as $field => $type) {
				if (is_int($field) && !preg_match('/(DESC|desc|ASC|asc)/', $type)) {
					$field = $type;
					$type = $order_type;
				}
				if (empty($order)) {
					$order = 'ORDER BY ' . $field . ' ' . $type;
				}else{
					$order = sprintf(", %s %s", $field, $type);
				}
			}
		} else {
			if (empty($order)) {
				$order = 'ORDER BY ' . $by . ' ' . $order_type;
			} else {
				$order .= sprintf(", `%s` %s", $by, $order_type);
			}
			
		}
		
		self::$order = $order;
		return $this;
	}



    public function lastInsertId(){
		return $this->pdo->lastInsertId();
	}

	public function CloseConnection()
	{
		$this->pdo = null;
	}
}
 
?>