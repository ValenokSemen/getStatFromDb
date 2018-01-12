<?php
/**
* 
*/
require_once('AbstractModel.php');

class PayOut extends AbstractModel
{

	public static $payoutTableSchema = array(
        'id' => PDO::PARAM_INT,
        'paidDate' => PDO::PARAM_STR,
        'timedifference' => PDO::PARAM_INT,
        'amount' => PDO::PARAM_STR
    );

    public static $avgTableSchema = array(
        'worker_name' => PDO::PARAM_STR,
        'avg' => PDO::PARAM_STR
    );
	
	function __construct()
	{
		parent::__construct();
	}

	public function payout_statistics()
	{
		$row = "";
		$this->allpayout =  parent::search_payout();
		foreach ($this->allpayout as $this->key) {
			$row .= "<tr>";
			$row .= "<td>{$this->key->id}</td>";
			$stack = [
						"id" => $this->key->id,
						"time" => $this->key->timedifference,
			];

			$ratio =  parent::search_payouthashrate($stack);
			$sum = self::summary($ratio);

			for ($i=0; $i < count($ratio); $i++) {

				$value = round(( (float) $ratio[$i]->avg / (float) $sum) * (float) $this->key->amount, 10);
				if ($ratio[$i]->status == "paid") {
					 $row .= "<td class='danger'>" . $value  . "</td>";
				}else{
					$row .= "<td>" .  $value . "</td>";
				}

				$row .= "<td>" .  round((float) $ratio[$i]->avg / (float) $sum, 3) . "</td>";
			}

			$row .= "<td>" . round($this->key->amount, 8) . "</td>";
			
			$row .= "<td>
						<div data-placement='top' data-toggle='tooltip' title='Edit'>
							<button class='btn btn-info btn-xs editrow' data-title='Edit' data-toggle='modal' data-target='#edit'>
								<span class='glyphicon glyphicon-pencil'></span>
							</button>
						</div>
					</td>";
			
			$row .= "</tr>";
		}
		
		$row .= "<tr>
					<td>Total:</td>
					<td id='sum1'></td>
				    <td id='ratio1'></td>
					<td id='sum2'></td>
				    <td id='ratio2'></td>
					<td id='sum3'></td>
				    <td id='ratio3'></td>
					<td id='sum4'></td>
					<td id='ratio4'></td>
					<td id='amount'></td>
					<td></td>
			  	</tr>";
		
		echo $row;
	}

	public function summary($ratio)
	{
		$sum = 0;
		foreach($ratio as $this->value)
		{
		   $sum += $this->value->avg;
		}
		return $sum;
	}
}

$payout = new PayOut();

?>