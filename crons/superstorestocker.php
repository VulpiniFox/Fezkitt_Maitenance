<?php

# addremovecage.php
# by Nicole Ward
# <http://snowwolfegames.com>
#
# Copyright (c) 2014 - SnowWolfe Games, LLC
# this script processes adding/removing cages to/from a player's ferretry

if (isset($_SERVER['HTTP_HOST']))
{
	exit('No joy.');
}

echo 'begin stocking<br />';
ini_set('display_errors', 'On');
require_once 'settings.php';
require_once Fez\INC_ROOT . 'configure.php';
$t1 = new Fez\code_timer;
$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '15'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('shop')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	echo $e->getMessage();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	echo $e->getMessage();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	echo $e->getMessage();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	echo $e->getMessage();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '12'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 1
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('shop')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '10'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 2
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('shop')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '8'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 3
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('shop')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '6'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 4
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('shop')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '4'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 5
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('shop')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

# restock cages

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '15'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('cagestore')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '12'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 1
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('cagestore')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '10'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 2
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('cagestore')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '8'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 3
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('cagestore')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '6'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 4
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('cagestore')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '4'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'instock',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 5
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('cagestore')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$upstockcage = mysql_query("UPDATE cagestore SET instock = instock + 12 WHERE instock = 0");

$upstock = mysql_query("UPDATE shop SET instock = instock + 12 WHERE instock = 0");

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '8'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 3
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('items')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '6'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 4
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('items')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

$Columns = [
	[
		'Field'	 => 'instock',
		'Value'	 => '4'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'rarity',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 5
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'active',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => TRUE
	]
];

try {
	$DB_Con->SetTable('items')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('UPDATE', 'Standard');
	if ($Result === FALSE)
	{
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		echo $e->getMessage();
		Fez\HandleError($error);
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
} finally {
	$DB_Con->ResetQuery();
}

// CREDIT ITEM TO THE JUNKYARD
/*
  $getija = mysql_query("SELECT id FROM items WHERE owner = 0");
  $itemja = mysql_num_rows($getija);

  if ($itemja < 10)
  {

  $getri = mysql_query("SELECT * FROM shop ORDER BY RAND() LIMIT 0,1");
  $store = mysql_fetch_array($getri);

  // NOW INSERT THE ITEM

  $inserti = mysql_query("INSERT INTO items(name, uses, description, category, image, owner, statboost) VALUES ('$store[name]','$store[uses]','$store[description]','$store[category]', '$store[image]', '0', '$store[statboost]')");
  }
 */
echo 'Successfully ran the superstore stocker cron!';
