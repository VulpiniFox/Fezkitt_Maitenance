<?php

# AttraxProcessingFunctions.class.php
# by Nicole Ward
# <http://snowwolfegames.com>
#
# Copyright (c) 2008 - 2014 - SnowWolfe Games, LLC
# this script allows the automated payment processing to process the items to the accounts

class AttraxProcessingFunctions
{

	protected $DB_Con;
	protected $Data;
	protected $AdminID;

	function __construct($DB_Con, $AdminID = 9999999)
	{
		$this->AdminID = $AdminID;
		$this->DB_Con = $DB_Con;

		# end constructor
	}


	function __destruct()
	{

		# end destructor
	}


	public function ProcessItem($ItemData)
	{
		$CreditQuantity = explode("-", $ItemData['ItemID']);

		$Columns = [[
			'Field'	 => 'FP',
			'Maths'	 => ' + ' . $CreditQuantity['1']
		]];
		$Params = [
			$ItemData['ForUser']
		];
		$Where = [[
			'FirstOperand'		 => 'id',
			'ExpressionOperator' => '='
		]];

		try {
			$this->DB_Con->SetTable('players')
				->SetColumns($Columns)
				->SetInputParams($Params)
				->SetWhere($Where)
				->SetLimit(1);
			$this->DB_Con->Query('UPDATE', 'Prepared');
			$this->DB_Con->BindInputParams()
				->ExecutePreparedQuery();
			$ExecuteCheck = TRUE;
		} catch (DBException $e) {
			throw $e;
			$ExecuteCheck = FALSE;
		} catch (Exception $e) {
			throw $e;
			$ExecuteCheck = FALSE;
		} finally {
			$this->DB_Con->CloseStmt()->ResetQuery();
		}

		if ($ExecuteCheck == TRUE)
		{
			# track the action taken
			Fez\TrackAdmins($this->DB_Con, $ItemData['ForUser'], 0, $CreditQuantity['1'] . ' FP', $ItemData['TxnID'], $this->AdminID);
		}
		return $ExecuteCheck;

		# end ProcessItem()
	}


	# end AttraxProcessingFunctions()
}
