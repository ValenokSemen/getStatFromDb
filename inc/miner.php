<?php

/**
* 
*/

require_once('AbstractModel.php');

class Miner extends AbstractModel
{

	protected $tableName = "miner";
    static private $allMiner;
    static private $payout = 0.5;

	public static $tableSchema = array(
        'session_id' => PDO::PARAM_INT,
        'repreportedHashrate' => PDO::PARAM_STR,
        'currentHashrate' => PDO::PARAM_STR,
        'averageHashrate' => PDO::PARAM_STR,
        'balance' => PDO::PARAM_STR,
        'currTime' => PDO::PARAM_STR,
        'ethPerMin' => PDO::PARAM_STR
    );

    function __construct() {
        parent::__construct();
        $this->lastMinerRow();
    }

    public function lastMinerRow()
    {
        $this->allMiner =  parent::search_statistics();
    }


	public function getTableName() {
        return self::$tableName;
    }

    public function get_statistics()
    {
        foreach ($this->allMiner as $this->key) {
            echo "<li class='list-group-item'>Reported Hashrate<span class='pull-right'>{$this->key->repreportedHashrate} MH/s</span></li>
            <li class='list-group-item'>Effective Hashrate [60 mins] <span class='pull-right'>{$this->key->currentHashrate} MH/s</span></li>
            <li class='list-group-item'>Average Hashrate [24 hrs] <span class='pull-right'>{$this->key->averageHashrate} MH/s</span></li>";
            
        }
    	
    }

    public function get_progress()
    {
        return number_format((self::get_balance()/self::$payout)*100);
    }

    public function get_remaining()
    {
        $this->remaining = number_format((self::$payout - self::get_balance()), 5);
        return $this->remaining;

    }

    public function get_balance()
    {
       $this->balance = number_format($this->allMiner[0]->balance,5);
       return $this->balance;
    }

    public function timeleft()
    {
        $ethpermin = $this->allMiner[0]->ethPerMin;
        $ethperhours = $ethpermin * 60;
        if ( $ethperhours != '0' ) {
                $this->timeleft =  $this->remaining / $ethperhours;
                return $this->timeleft;
        }
        return 0;
    }

    public function nextpayout()
    {
        return date("D d M, H:i:s", time() + ($this->timeleft * 3600) );
    }

}

$miner = new Miner();

?>
