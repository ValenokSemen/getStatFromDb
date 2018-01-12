<?php

require_once('config.php');	
require_once('AbstractModel.php');

/**
* 
*/
class Update extends AbstractModel 
{

	function __construct()
	{
		parent::__construct();
	}

	public function update_payout($post)
	{

		$result =  parent::update($post);
		return $result;
	}
}


if (!empty($_POST)) {
	$payout = new Update();
	$result = $payout->update_payout($_POST);
}

?>