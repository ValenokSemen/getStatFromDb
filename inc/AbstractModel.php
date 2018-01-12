<?php
/**
* @author 
* @version 0.1a
*/

require_once('functions_db.php');

class AbstractModel
{
	private $db;
	
	function __construct()
	{
		$this->db = new DB();
	}

	public function all_statistics()
	{
		$sql = "SELECT * FROM " . $this->tableName;
		return $this->db->query($sql, null, static::$tableSchema);
	}

	public function search_statistics()
	{
		$sql = "SELECT * FROM " . $this->tableName;
		return $this->db->order_by('session_id')->limit(1)->query($sql, null, static::$tableSchema);
	}

	public function search_workerstat()
	{
		$sql = "SELECT worker_name, avg(currHashRate) as avg_c, avg(reportHashRate) as avg_r, MAX(miner.currTime) as time FROM " . $this->tableName . " ";
		$sql .= "LEFT JOIN miner ON statistics.session_id=miner.session_id ";
		$sql .= "LEFT JOIN worker w  ON w.id = statistics.worker_id ";
		$sql .= "WHERE miner.currTime >= now() - INTERVAL ? HOUR ";
		$sql .= "GROUP BY worker_id ";

		return $this->db->order_by('worker_name', 'ASC')->query($sql, array('24'), static::$tableSchema);
	}

	public function search_payout()
	{
		$sql = "SELECT t.id, t.paidDate, t.timedifference, po.amount from ";
		$sql .= "(SELECT B.id, B.paidDate, TIMESTAMPDIFF(MINUTE,A.paidDate,B.paidDate) AS timedifference
				FROM payout A INNER JOIN payout B
				WHERE B.id IN (SELECT MIN(C.id) FROM payout C WHERE C.id > A.id)
				ORDER BY A.id ASC) t ";
		$sql .= "LEFT JOIN paidstatus ps ON ps.payout=t.id ";
		$sql .= "LEFT JOIN payout po ON po.id=t.id ";
		$sql .= "WHERE ps.paidstatus = ? ";
		$sql .= "GROUP BY payout";

		return $this->db->query($sql, array('unpaid'), static::$payoutTableSchema);

	}

	public function search_payouthashrate($array)
	{
		$sql = "SELECT w.worker_name as name, avg(currHashRate) as avg, paidstatus as status FROM statistics ";
		$sql .= "LEFT JOIN miner ON statistics.session_id=miner.session_id ";
		$sql .= "LEFT JOIN worker w  ON w.id = statistics.worker_id ";
		$sql .= sprintf('LEFT JOIN paidstatus p ON p.worker_name = w.worker_name and p.payout = %s ', $array["id"]);
		$sql .= sprintf('WHERE miner.currTime >= (select paidDate from payout where id = %s) - INTERVAL ? MINUTE and ', $array["id"]);
		$sql .= sprintf('miner.currTime <= (select paidDate from payout where id = %s) ', $array["id"]);
		$sql .= "GROUP BY worker_id ";


		return $this->db->order_by('w.worker_name', 'ASC')->query($sql, array($array["time"]), static::$avgTableSchema);

	}

	public function update($post)
	{
		if ($post["worker"] == "all") {
			$sql = "update paidstatus set paidstatus = ? where payout = ?";
			return $this->db->query($sql, array($post["paidstatus"], $post["payoutid"]));
		}else{
			$sql = "update paidstatus set paidstatus = ? where payout = ? and worker_name = ?";
			return $this->db->query($sql, array($post["paidstatus"], $post["payoutid"], $post["worker"]));
		}

	}
}
?>


<!-- SELECT worker_name, avg(currHashRate) as avg_c, avg(reportHashRate) as avg_r, now() as time FROM statistics
        LEFT JOIN miner ON statistics.session_id=miner.session_id
        LEFT JOIN worker w  ON w.id = statistics.worker_id
        WHERE miner.currTime >= now() - INTERVAL 10 DAY
        GROUP BY worker_id
		  ORDER BY worker_name ASC; -->

		  <!-- SELECT worker_name, avg(currHashRate) FROM statistics
 LEFT JOIN miner ON statistics.session_id=miner.session_id
 LEFT JOIN worker w  ON w.id = statistics.worker_id
 WHERE miner.currTime >= (select paidDate from payout where id = 4979636) - INTERVAL 15 MINUTE and
 miner.currTime <= now()
 GROUP BY worker_id
 ORDER BY worker_name ASC; -->