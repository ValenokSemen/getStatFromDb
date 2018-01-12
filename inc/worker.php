<?php
/**
* 
*/

require_once('AbstractModel.php');

class Worker extends AbstractModel
{
	
	protected $tableName = "statistics";

	function __construct()
	{
		parent::__construct();
	}

	public static $tableSchema = array(
        'worker_name' => PDO::PARAM_STR,
        'avg_c' => PDO::PARAM_STR,
        'avg_r' => PDO::PARAM_STR,
        'time' => PDO::PARAM_STR
    );

    public function worker_statistics()
    {
    	$row = "";
    	$this->allworker =  parent::search_workerstat();
    	foreach ($this->allworker as $this->key) {
    		$row .=  "<tr>
                            <td>{$this->key->worker_name}</td>
                            <td>" . number_format($this->key->avg_c, 2) . " MH/s</td>
                            <td>" . number_format($this->key->avg_r, 2) . " MH/s</td>
                            <td>{$this->key->time}</td>
                     </tr>";
    	}
    	echo $row;
    }

}

$worker = new Worker();
?>