<?php

#  DBConnect.inc.php
#  by Nicole Ward
#  <http://snowwolfegames.com>
#
#  Copyright (c)  - SnowWolfe Games, LLC
#  this script creates the main database connection
#

try {
	$DB_Con = new Fez\MySQL_DB();
	$DB_Con->SetDebug(TRUE);

	$ConnectionCheck = $DB_Con->Connect()->Connected();
} catch (\DBException $e) {
	if ($DB_Con)
	{
		$DB_Con = NULL;
	}
	ignore_user_abort(true);
	$LogData = "DBException caught " . __FILE__ . " " . __LINE__ . " Exception thrown in: " . $e->getFile() . " On line: " . $e->getLine() . "  Error Text:" . $e->getMessage() . "\r\nError #: " . $e->getCode();
	Fez\HandleError($LogData);
} catch (\Exception $e) {
	if ($DB_Con)
	{
		$DB_Con = NULL;
	}
	ignore_user_abort(true);
	$LogData = "Exception caught " . __FILE__ . " " . __LINE__ . " Exception thrown in: " . $e->getFile() . " On line: " . $e->getLine() . "  Error Text:" . $e->getMessage() . "\r\nError #: " . $e->getCode();
	Fez\HandleError($LogData);
}
if ($ConnectionCheck == FALSE)
{
	throw new \DBException("Database connection failure.");
}
