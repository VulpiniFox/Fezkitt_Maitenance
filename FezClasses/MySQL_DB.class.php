<?php

# MySQL_DB.class.php
# by Nicole Ward
# <http://snowwolfegames.com>
# <nikki@snowwolfegames.com>
#
# Copyright (c) 2009 - 2014 - SnowWolfe Games, LLC
# This file is part of DatabaseAbstractionLayer.
# This script handles mysql database interactions.
# properties:
# $DB_Con
# - protected
# - resource
# - holds the connection handle to the db
# $Debug
# - protected
# - boolean
# - flag if debugging is on/off
# $Table
# - protected
# - string
# - holds the name of the current table
# $SelectColumns
# - protected
# - array
# - holds the column names for a select query
# $JoinTables
# - protected
# - array
# - holds the tables for join queries
# $Select = \NULL;
# $Where
# - protected
# - string
# - holds the where information
# $OrderBy
# - protected
# - string
# - holds order by information
# $GroupBy
# - protected
# - string
# - holds group by information
# $Limit
# - protected
# - integer
# - holds limit number
# $Offset
# - protected
# - integer
# - holds offset number
# $SelectClause
# - protected
# - string
# - holds the select portion of a query
# $TableClause
# - protected
# - string
# - holds the table portion of a query
# $WhereClause
# - protected
# - string
# - holds the where portion of a query
# $OrderByClause
# - protected
# - string
# - holds the order by portion of a query
# $GroupByClause
# - protected
# - resource
# - holds the group by portion of a query
# $LimitClause
# - protected
# - resource
# - holds the limit portion of a query
# $AutoCommit
# - protected
# - resource
# - holds the connection handle to the db
# $SQL
# - protected
# - string
# - holds the complete sql query
# $Result
# - protected
# - resource
# - holds result resource
# $SQLStmt
# - protected
# - resource
# - holds the prepared statement resource
#
# methods:
# __construct()
# __clone()
# __destruct()
# -- calls:
# 		- self::Close()
# SetDebugging()
# - Sets the $Debug variable
# -- parameters:
# -- $Debug
# 		- boolean
# 		- sets debug on (\TRUE) or off (\FALSE)
# -- calls:
# 		- Sanitize()
# SetTable()
# - sets the table for a query
# -- paramaters:
# -- $Table
# 		- string
# 		- table's name
# -- $Alias
# 		- string
# 		- alias for table, optional
# -- calls:
# 		- MySQL_Query::SetTable()
# SetJoinTables()
# - sets the join table(s) for a query
# -- paramaters:
# -- $JoinTables
# 		- array
# 		- names, join methods, join types, join statements
# -- calls:
# 		- MySQL_Query::SetJoinTable()
# SetLockTables()
# - sets the tables to be locked
# -- paramaters:
# -- $LockTables
# 		- array
# 		- names of tables to lock
# -- calls:
# 		- MySQL_Query::SetLockTables()
# SetColumns()
# -sets the column(s) to be used for a query
# -- paramaters:
# -- $Columns
# 		- array
# 		- names of columns to be used
# -- calls:
# 		- MySQL_Query::SetColumns()
# SetWhere()
# - sets the arguments to be used in a where clause
# -- paramaters:
# -- $LockTables
# 		- array
# 		- arguments to build a where clause;
# 		- clause operator, first operand, expression operator, second operand
# -- calls:
# 		- MySQL_Query::SetWhere()
# SetOrderBy()
# - sets the column to order a query by
# -- paramaters:
# -- $OrderBy
# 		- string
# 		- name of column to order by
# -- $Direction
# 		- string
# 		- direction to sort, optional
# -- calls:
# 		- MySQL_Query::SetOrderBy()
# SetGroupBy()
# - sets the column to group a query by
# -- paramaters:
# -- $GroupBy
# 		- string
# 		- name of column to group by
# -- calls:
# 		- MySQL_Query::SetGroupBy()
# SetLimit()
# - sets the limit for a query
# -- paramaters:
# -- $Limit
# 		- integer
# 		- number of rows to return
# -- $Offset
# 		- integer
# 		- row to start on, optional
# -- calls:
# 		- MySQL_Query::SetLimit()
# SetInsertValues()
# - sets the values for an insert query
# -- paramaters:
# -- $Values
# 		- array
# 		- values for each column in an insert query
# -- calls:
# 		- MySQL_Query::SetInsertValues()
# SetDuplicateKey()
# - sets the columns to be updated on duplicate key
# -- paramaters:
# -- $DuplicateKey
# 		- array
# 		- columns to update on duplicate key
# -- calls:
# 		- MySQL_Query::SetDuplicateKey()
# SetEngine()
# - sets the engine to be used when creating a (temporary) table
# -- paramaters:
# -- $Engine
# 		- string
# 		- engine to use
# -- calls:
# 		- MySQL_Query::SetEngine()
# GetParamType()
# - determines the type of a paramater
# -- paramaters:
# -- $Value
# 		- string
# 		- paramater to be examined
# -- returns:
# 		- type of submitted value
# StartDebugging()
# - opens the debugging log up for use
# -- calls:
# 		- Logger::init()
# 		- Logger::OpenLogFile()
# Connect()
# - connects to mysql using mysqli to the main game db
# -- paramaters:
# -- calls:
# -- returns:
# GetDBInstance()
# - gets an instance of this class for other code
# -- calls:
# 		- self::construct()
# select_db()
# - changes db
# -- parameters:
# -- $DB
#			- string
#			- name of the desired database to connect to
# connected()
# - checks that there is a connection to mysql
# HandleError()
# - handles triggering error logging
# -- parameters:
# -- $error
# close()
# - closes the connection
# PreparedStmtQuery()
# - queries any table(s), any number of fields, any where arguments
# -- parameters:
# -- $QueryArr
# -- calls:
# -- self::PrepareQuery()
# PreparedStmtUpdate()
# - updates any table, any number of fields, any where arguments
# -- parameters:
# -- $QueryArr
# -- calls:
# -- self::PrepareQuery()
# PreparedStmtDelete()
# - deletes from any table, any where arguments
# -- parameters:
# -- $QueryArr
# -- calls:
# -- self::PrepareQuery()
# PreparedStmtInsert()
# - inserts into any table, any number of fields/values
# -- parameters:
# -- $QueryArr
# -- calls:
# -- self::PrepareQuery()
# RegularQuery()
# - queries any table(s), any number of fields, any where arguments
# -- parameters:
# -- $QueryArr
# RegularUpdate()
# - updates any table, any number of fields, any where arguments
# -- parameters:
# -- $QueryArr
# -- calls:
# -- self::RunQuery()
# -- self::HandleError()
# RegularDelete()
# - deletes from any table, any where arguments
# -- parameters:
# -- $QueryArr
# -- calls:
# -- self::RunQuery()
# -- self::HandleError()
# RegularInsert()
# - inserts into any table, can do insert/select
# -- parameters:
# -- $QueryArr
# -- calls:
# -- self::RunQuery()
# -- self::HandleError()
# TruncateTable()
# - truncates a table
# -- parameters:
# -- $QueryArr
# -- calls:
# -- self::RunQuery()
# -- self::HandleError()
# OptimizeTable()
# - optimizes a table
# -- parameters:
# -- $QueryArr
# -- calls:
# -- self::RunQuery()
# -- self::HandleError()
# ShowTables
# - gets a list of the tables in the database
# PrepareQuery()
# - prepares a prepared statement query
# -- calls:
# -- self::HandleError()
# BindInputParams()
# - binds the input parameters for a prepared statement query
# -- parameters:
# -- $QueryObj
# -- calls:
# -- self::HandleError()
# ExecutePreparedQuery()
# - executes prepared statement query and if needed stores the result
# -- calls:
# -- self::HandleError()
# FetchPreparedResults()
# -- parameters:
# -- $ReturnFormat
# -- calls:
# -- self::HandleError()
# CloseStmt()
# RunQuery()
# - executes queries
# -- calls:
# -- self::HandleError()
# FetchResults()
# -- parameters:
# -- $ResultResource
# -- $ReturnFormat
# -- calls:
# -- self::HandleError()
# CloseResult()
# -- parameters:
# -- $ResultResource
# num_rows()
# -- parameters:
# -- $ResultResource
# affected_rows()

namespace Fez;

if (0 > \version_compare(\PHP_VERSION, '5'))
{
	throw new \Exception('This file was generated for PHP 5');
}

/**
 * include DBInterface
 *
 * @author Nicole Ward, <nikki@snowwolfegames.com>
 */
require_once('DBInterface.class.php');

/* user defined includes */

/* user defined constants */

final class MySQL_DB extends DBInterface
{

	const DB_HOST = 'swgdb.cgnwvdsnszvj.us-east-1.rds.amazonaws.com';
	const DB_USER = 'fez_user';
	const DB_PASS = 'T8rmQ6c!P$byGWA';
	const DB_NAME = 'fezgamedb';

	# \InvalidArgumentException Codes
	const MISSING_DATA = 1;
	const WRONG_TYPE = 2;
	const WRONG_VALUE = 3;

	protected $DB_Con = \NULL;
	protected $Debug = \FALSE;
	public $Debugging = \NULL;
	protected $QueryObj = \NULL;
	protected $AutoCommit = \NULL;
	protected $SQL = \NULL;
	protected $CurrentResult = \NULL;
	protected $SQLStmt = \NULL;
	protected $InputParams = \NULL;
	protected $InputBindParams = \NULL;

	public function __construct()
	{

		# end __construct()
	}


	public function __clone()
	{
		\trigger_error('Clone is not allowed.', \E_USER_ERROR);

		# end clone()
	}


	public function __destruct()
	{
		$this->Close();

		# end __destruct()
	}


	public function ResetQuery()
	{
		$this->QueryObj->ResetQuery();
		$this->SQL = \NULL;
		$this->InputParams = \NULL;
		$this->InputBindParams = \NULL;

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Reset query.';
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;
		# end ResetQuery()
	}


	public function SetDebug($Debug)
	{
		$this->Debug = Sanitize($Debug, 'boolean');

		if ($this->Debug === \TRUE)
		{
			$this->StartDebugging();
		}

		return $this;

		# end SetDebug()
	}


	public function SetTable($Table, $Alias = \NULL)
	{
		$this->QueryObj->SetTable($Table, $Alias);
		return $this;

		# end SetTable()
	}


	public function SetJoinTables(Array $JoinTables)
	{
		$this->QueryObj->SetJoinTables($JoinTables);
		return $this;

		# end SetJoinTables()
	}


	public function SetLockTables(Array $LockTables)
	{
		$this->QueryObj->SetLockTables($LockTables);
		return $this;

		# end SetLockTables()
	}


	public function SetColumns(Array $Columns)
	{
		$this->QueryObj->SetColumns($Columns);
		return $this;

		# end SetColumns()
	}


	public function SetInputParams(Array $Params)
	{
		$this->InputParams = array();
		foreach ($Params as $Value)
		{
			$this->InputParams[] = Sanitize($Value);
		}
		return $this;

		# end SetInputParams()
	}


	public function SetWhere(Array $Where)
	{
		$this->QueryObj->SetWhere($Where);
		return $this;

		# end SetWhere()
	}


	public function SetOrderBy($OrderBy, $Direction = \NULL)
	{
		$this->QueryObj->SetOrderBy($OrderBy, $Direction);
		return $this;

		# end SetOrderBy
	}


	public function SetGroupBy($GroupBy)
	{
		$this->QueryObj->SetGroupBy($GroupBy);
		return $this;

		# end SetGroupBy
	}


	public function SetLimit($Limit, $Offset = \NULL)
	{
		$this->QueryObj->SetLimit($Limit, $Offset);
		return $this;

		# end SetLimit()
	}


	public function SetInsertValues(Array $Values)
	{
		$this->QueryObj->SetInsertValues($Values);
		return $this;

		# end SetInsertValues()
	}


	public function SetDuplicateKey($DuplicateKey)
	{
		$this->QueryObj->SetDuplicateKey($DuplicateKey);
		return $this;

		# end SetDuplicateKey()
	}


	public function SetEngine($Engine)
	{
		$this->QueryObj->SetEngine($Engine);
		return $this;

		# end SetEngine()
	}


	protected function GetParamType($Value)
	{
		# get the value's type
		$ValueType = \gettype($Value);

		# assign it a prepared statement type
		switch ($ValueType) {
			case 'integer':
				$ParamType = 'i';
				break;
			case 'double':
				$ParamType = 'd';
				break;
			case 'string':
				$ParamType = 's';
				break;
			default:
				$ParamType = \FALSE;
				break;
		}

		return $ParamType;

		# end GetParamType
	}


	protected function StartDebugging()
	{
		try {
			$this->Debugging = new Logger();
			$this->Debugging->init('sqldebug', \TRUE, 'Medium', 'Debugging', \FALSE);
			$this->Debugging->SetFilePath(OTHER_LOG);
			$LogData = 'Beginning debug log.';
			$this->Debugging->OpenLogFile()->WriteToLog($LogData, \TRUE);
		} catch (Exception $exc) {
			throw $exc;
		}


		# end StartDebugging()
	}


	public function Connect()
	{
		# get our database connection
		$this->DB_Con = new \mysqli(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);

		# check connection
		if ($this->DB_Con->connect_errno)
		{
			throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' Connection'
			. ' failed. '
			. $this->DB_Con->connect_error, $this->DB_Con->connect_errno);
		}

		$this->DB_Con->query("SET NAMES 'utf8'");

		# get our query object
		$this->QueryObj = new MySQL_Query($this);
		$this->QueryObj->SetDebug($this->Debug);
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Connected.';
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;

		# end Connect()
	}


	public function Connected()
	{
		$returnValue = \NULL;
		if ($this->DB_Con)
		{
			$returnValue = ($this->DB_Con->ping() == \TRUE)
				? \TRUE
				: \FALSE;
		} else
		{
			$returnValue = \FALSE;
		}
		return $returnValue;

		# end Connected
	}


	public function Close()
	{
		if ($this->DB_Con)
		{
			$this->DB_Con = \NULL;
		}
		if ($this->QueryObj)
		{
			$this->QueryObj = \NULL;
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Close connection.';
			$this->Debugging->WriteToLog($LogData);
		}
		# end Close()
	}


	public function SelectDB($Database)
	{
		$SelectDBCheck = \NULL;
		if (!$this->DB_Con->select_db($Database))
		{
			throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' Database'
			. ' selection failed. ' . \PHP_EOL . $this->Error()
			. \PHP_EOL . $this->ErrorNo(), $this->ErrorNo());
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Select DB: ' . $Database;
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;

		# end SelectDB()
	}


	public function ErrorNo()
	{
		$returnValue = $this->DB_Con->errno;

		return $returnValue;

		# end ErrorNo()
	}


	public function Error()
	{
		$returnValue = $this->DB_Con->error;

		return $returnValue;

		# end Error()
	}


	public function EscapeString($String)
	{
		$returnValue = \NULL;
		$returnValue = $this->DB_Con->real_escape_string($String);
		return $returnValue;

		# end EscapeString()
	}


	public function InsertID()
	{
		$returnValue = $this->DB_Con->insert_id;

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__;
			$this->Debugging->WriteToLog($LogData);
		}

		return $returnValue;

		# end InsertID()
	}


	public function AutoCommit($Setting)
	{
		$this->DB_Con->autocommit($Setting);

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Setting: ' . $Setting;
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;

		# end AutoCommit()
	}


	public function StartTrans()
	{

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Table: ' . $this->Table;
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;

		# end StartTrans()
	}


	public function RollbackTrans()
	{
		if (!$this->DB_Con->rollback())
		{
			throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' Rollback'
			. ' failed. '
			. $this->Error() . ' ' . $this->ErrorNo(), $this->ErrorNo());
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__;
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;

		# end RollbackTrans()
	}


	public function CommitTrans()
	{
		$CommitCheck = $this->DB_Con->commit();

		if ($this->Debug == \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__;
			$this->Debugging->WriteToLog($LogData);
		}

		if ($CommitCheck === \FALSE)
		{
			throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' Commit'
			. ' transaction failed. ' . \PHP_EOL . $this->Error() . \PHP_EOL
			. $this->ErrorNo(), $this->ErrorNo());
		}

		return $this;

		# end CommitTrans()
	}


	protected function PrepareQuery()
	{
		if (empty($this->SQL))
		{
			throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
			. ' $this->SQL is empty.', self::MISSING_DATA);
		}

		$this->SQLStmt = $this->DB_Con->prepare($this->SQL);
		$PrepareCheck = (\gettype($this->SQLStmt) === 'object')
			? \TRUE
			: \FALSE;

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Prepare query: ';
			$LogData .= ($PrepareCheck == \TRUE)
				? 'Statement prepared. '
				: 'Prepare statement failed. ';
			$this->Debugging->WriteToLog($LogData);
		}

		if ($PrepareCheck === \FALSE)
		{
			throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' Prepare '
			. "query failed. " . \PHP_EOL . $this->SQL . \PHP_EOL
			. \PHP_EOL . $this->Error() . \PHP_EOL
			. $this->ErrorNo(), $this->ErrorNo());
		}

		return $PrepareCheck;

		# end PrepareQuery()
	}


	public function BindInputParams()
	{
		$returnValue = \NULL;
		if (empty($this->InputParams) ||
			$this->SQLStmt === \FALSE ||
			\gettype($this->SQLStmt) !== 'object' ||
			empty($this->SQL))
		{
			if (empty($this->InputParams))
			{
				throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
				. ' Bind input params failed.'
				. ' Missing input params.', self::MISSING_DATA);
			} elseif (empty($this->SQLStmt))
			{
				throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
				. ' Bind input params failed.'
				. ' Missing SQLStmt.', self::MISSING_DATA);
			} elseif (empty($this->SQL))
			{
				throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
				. ' Bind input params failed.'
				. ' Missing SQL.', self::MISSING_DATA);
			}
		}

		$ParamTypes = \NULL;
		$Params = array();

		foreach ($this->InputParams as $Key => $Value)
		{
			$ParamTypes .= $this->GetParamType($Value);
			$Params[$Key] = $Value;
		}
		\array_unshift($Params, $ParamTypes);

		# bind input params requires arguments as references
		# and it is enforced as of php 5.3 soooo
		$this->InputBindParams = array();
		foreach ($Params as $Key => &$Value)
		{
			$this->InputBindParams[$Key] = &$Value;
		}

		if (!\call_user_func_array(array(
				&$this->SQLStmt,
				'mysqli_stmt::bind_param'), $this->InputBindParams))
		{
			throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' Bind input'
			. ' params failed. ' . \PHP_EOL . $this->Error() . \PHP_EOL
			. $this->ErrorNo() . ' '
			. \var_export($this->InputParams, true), $this->ErrorNo());
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Input params: ' . \var_export($this->InputParams, true);
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;

		# end BindInputParams()
	}


	public function ExecutePreparedQuery()
	{
		if ($this->SQLStmt === \FALSE ||
			\gettype($this->SQLStmt) !== 'object')
		{
			throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
			. ' Execute Prepared Query failed. SQLStmt not set.', self::MISSING_DATA);
		}

		if (!$this->SQLStmt->execute())
		{
			throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' Execute'
			. ' prepared query failed. ' . $this->Error() . ' '
			. $this->ErrorNo(), $this->ErrorNo());
		}

		# if this is a select query we need to call store_result
		if (\stripos($this->SQL, 'SELECT') !== \FALSE)
		{
			if (!$this->SQLStmt->store_result())
			{
				throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' Store'
				. ' result failed. ' . \PHP_EOL . $this->Error() . \PHP_EOL
				. $this->ErrorNo(), $this->ErrorNo());
			}
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;

		# end ExecutePreparedQuery()
	}


	public function FetchPreparedResults($ReturnFormat = 'assoc')
	{
		if ($this->SQLStmt === \FALSE ||
			\gettype($this->SQLStmt) !== 'object')
		{
			throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
			. ' $this->SQLStmt is not set.', self::MISSING_DATA);
		}

		if ($this->NumRows() === 0)
		{
			return \FALSE;
		}

		switch ($ReturnFormat) {
			case 'assoc':
				$Result = array();
				$StmtBindCheck = $this->StmtBindAssoc($Result);
				if ($StmtBindCheck == \FALSE)
				{
					throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' '
					. 'Fetch prepared results (assoc) failed at '
					. 'stmtbindassoc. ' . \PHP_EOL . $this->Error() . \PHP_EOL
					. $this->ErrorNo(), $this->ErrorNo());
				}
				if ($this->SQLStmt->fetch())
				{
					if ($this->Debug == \TRUE)
					{
						$LogData = __FILE__ . ' ' . __METHOD__ . ' Fetch '
							. 'prepared results (assoc)'
							. \var_export($Result, true);
						$this->Debugging->WriteToLog($LogData);
					}
					return $Result;
				}
				break;
			case 'object':
				$Result = new \stdClass();
				$StmtBindCheck = $this->StmtBindObject($Result);
				if ($StmtBindCheck == \FALSE)
				{
					throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' '
					. 'Fetch prepared results (object) failed at '
					. 'stmtbindobject. ' . \PHP_EOL . $this->Error() . \PHP_EOL
					. $this->ErrorNo(), $this->ErrorNo());
				}
				if ($this->SQLStmt->fetch())
				{
					if ($this->Debug == \TRUE)
					{
						$LogData = __FILE__ . ' ' . __METHOD__ . ' Fetch '
							. 'prepared results (object)'
							. \var_export($Result, true);
						$this->Debugging->WriteToLog($LogData);
					}
					return $Result;
				}
				break;
			case 'array':
			default:
				$Result = array();
				$StmtBindCheck = $this->StmtBindArray($Result);
				if ($StmtBindCheck == \FALSE)
				{
					throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' '
					. 'Fetch prepared results (array) failed at '
					. 'stmtbindarray. ' . \PHP_EOL . $this->Error() . \PHP_EOL
					. $this->ErrorNo(), $this->ErrorNo());
				}
				if ($this->SQLStmt->fetch())
				{
					if ($this->Debug == \TRUE)
					{
						$LogData = __FILE__ . ' ' . __METHOD__ . ' Fetch '
							. 'prepared results (array)'
							. \var_export($Result, true);
						$this->Debugging->WriteToLog($LogData);
					}
					return $Result;
				}
				break;
		}

		# end FetchPreparedResults()
	}


	protected function StmtBindArray(&$ReturnArr)
	{
		$ResultMeta = $this->SQLStmt->result_metadata();
		if ($ResultMeta == \FALSE)
		{
			return \FALSE;
		}
		$FieldNames = array();
		$ReturnArr = array();
		$FieldNames[0] = &$this->SQLStmt;
		$count = 1;

		while ($Field = $ResultMeta->fetch_field())
		{
			$FieldNames[$count] = & $ReturnArr[];
			$count++;
		}

		$StmtBindCheck = \call_user_func_array('mysqli_stmt_bind_result', $FieldNames);
		$ResultMeta->close();
		if ($this->Debug == \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Binding result array ';
			$LogData .= (!empty($StmtBindCheck))
				? 'succeeded.'
				: 'failed.';
			$this->Debugging->WriteToLog($LogData);
		}

		return $StmtBindCheck;

		# end StmtBindArray()
	}


	protected function StmtBindAssoc(&$ReturnArr)
	{
		$ResultMeta = $this->SQLStmt->result_metadata();
		if ($ResultMeta == \FALSE)
		{
			return \FALSE;
		}
		$FieldNames = array();
		$ReturnArr = array();
		$FieldNames[0] = &$this->SQLStmt;
		$count = 1;

		while ($Field = $ResultMeta->fetch_field())
		{
			$FieldNames[$count] = & $ReturnArr[$Field->name];
			$count++;
		}

		$StmtBindCheck = \call_user_func_array('mysqli_stmt_bind_result', $FieldNames);
		$ResultMeta->close();
		if ($this->Debug == \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Binding result assoc ';
			$LogData .= (!empty($StmtBindCheck))
				? 'succeeded.'
				: 'failed.';
			$this->Debugging->WriteToLog($LogData);
		}

		return $StmtBindCheck;

		# end StmtBindAssoc()
	}


	protected function StmtBindObject(&$ReturnObj)
	{
		$ResultMeta = $this->SQLStmt->result_metadata();
		if ($ResultMeta == \FALSE)
		{
			return \FALSE;
		}
		$FieldNames = array();
		$ReturnObj = new \stdClass;
		$FieldNames[0] = &$this->SQLStmt;
		$count = 1;

		while ($Field = $ResultMeta->fetch_field())
		{
			$fn = $Field->name;
			$FieldNames[$count] = & $ReturnObj->$fn;
			$count++;
		}

		$StmtBindCheck = \call_user_func_array('mysqli_stmt_bind_result', $FieldNames);
		$ResultMeta->close();
		if ($this->Debug == \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Binding result object ';
			$LogData .= (!empty($StmtBindCheck))
				? 'succeeded.'
				: 'failed.';
			$this->Debugging->WriteToLog($LogData);
		}

		return $StmtBindCheck;

		# end StmtBindObject()
	}


	public function CloseStmt()
	{
		if (!empty($this->SQLStmt))
		{
			$this->SQLStmt->close();
			unset($this->SQLStmt);
		}

		if ($this->Debug == \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__;
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;

		# end CloseStmt()
	}


	protected function RunQuery($QueryType)
	{
		if (empty($this->SQL))
		{
			throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
			. ' $this->SQL is empty. ', self::MISSING_DATA);
		}

		$Return = FALSE;

		switch ($QueryType) {
			case 'SELECT':
			case 'SHOW TABLES':
			case 'SHOW COLUMNS':
			case 'DESCRIBE':
			case 'EXPLAIN':
				# these return a result set
				if (!$Return = $this->DB_Con->query($this->SQL))
				{
					throw new \DBException(__FILE__ . ' ' . __METHOD__
					. ' run query failed. ' . \PHP_EOL . $this->Error()
					. \PHP_EOL . $this->SQL
					. \PHP_EOL . $this->ErrorNo(), $this->ErrorNo());
				}
				break;
			case 'INSERT':
			case 'UPDATE':
			case 'DELETE':
			case 'ALTER TABLE':
			case 'OPTIMIZE TABLE':
			case 'TRUNCATE TABLE':
			case 'CREATE TEMPORARY TABLE':
			case 'DROP TEMPORARY TABLE':
			case 'LOCK TABLES':
			case 'UNLOCK TABLES':
				# these do not return result sets
				if (!$Return = $this->DB_Con->query($this->SQL))
				{
					throw new \DBException(__FILE__ . ' ' . __METHOD__
					. ' run query failed. ' . \PHP_EOL . $this->Error()
					. \PHP_EOL . $this->SQL
					. \PHP_EOL . $this->ErrorNo(), $this->ErrorNo());
				}
				break;
			default:
				throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
				. ' Unknown query type. ', self::WRONG_VALUE);
				break;
		}

		if ($this->Debug == \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->Debugging->WriteToLog($LogData);
		}

		return $Return;

		# end RunQuery()
	}


	public function FetchResults($Result, $ReturnFormat = 'assoc', $ReturnArrFormat = 'MYSQLI_NUM')
	{
		if (empty($Result))
		{
			throw new \Exception(__FILE__ . ' ' . __METHOD__
			. ' $this->CurrentResult is not set/empty.', self::MISSING_DATA);
		}

		$ResultCheck = (\gettype($Result) === 'object')
			? \TRUE
			: \FALSE;

		if ($ResultCheck === \FALSE)
		{
			throw new \DBException(__FILE__ . ' ' . __METHOD__ . ' Cannot fetch'
			. ' results because CurrentResults is not an object. '
			. \PHP_EOL . $this->Error() . \PHP_EOL
			. $this->ErrorNo(), $this->ErrorNo());
		}

		if ($this->NumRows($Result) > 0)
		{
			switch ($ReturnFormat) {
				case 'array':
					switch ($ReturnArrFormat) {
						case 'MYSQLI_NUM':
							return $Result->fetch_array(\MYSQLI_NUM);
							break;
						case 'MYSQLI_BOTH':
							return $Result->fetch_array(\MYSQLI_BOTH);
							break;
						case 'MYSQLI_ASSOC':
						default:
							return $Result->fetch_array(\MYSQLI_ASSOC);
							break;
					}
					break;
				case 'object':
					return $Result->fetch_object();
					break;
				case 'assoc':
				default:
					return $Result->fetch_assoc();
					break;
			}
		} else
		{
			return \NULL;
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__;
			$this->Debugging->WriteToLog($LogData);
		}

		# end FetchResults()
	}


	public function CloseResult($Result)
	{
		if ($Result)
		{
			$Result->close();
			unset($Result);
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__;
			$this->Debugging->WriteToLog($LogData);
		}

		return $this;

		# end CloseResult()
	}


	public function NumRows($Result = NULL)
	{
		$returnValue = \NULL;
		if (isset($this->SQLStmt))
		{
			$returnValue = $this->SQLStmt->num_rows;
		} elseif (isset($Result))
		{
			$returnValue = $Result->num_rows;
		} else
		{
			throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
			. ' $Result is not set/empty. ', self::MISSING_DATA);
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . 'Number of rows: ' . $returnValue;
			$this->Debugging->WriteToLog($LogData);
		}

		return $returnValue;

		# end NumRows()
	}


	public function AffectedRows()
	{
		$returnValue = \NULL;
		if (isset($this->SQLStmt))
		{
			$returnValue = $this->SQLStmt->affected_rows;
		} else
		{
			$returnValue = $this->DB_Con->affected_rows;
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . 'Number of affected '
				. 'rows: ' . $returnValue;
			$this->Debugging->WriteToLog($LogData);
		}

		return $returnValue;

		# end AffectedRows()
	}


	public function Query($QueryType = \NULL, $RunAs = 'Standard')
	{
		if (empty($QueryType))
		{
			throw new \InvalidArgumentException(__FILE__ . ' ' . __METHOD__
			. ' $QueryType is not set/empty. ', self::MISSING_DATA);
		}

		$Return = FALSE;

		try {
			$this->QueryObj->SetQuery($QueryType);
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		$this->SQL = $this->QueryObj->GetSQL();
		try {
			switch ($RunAs) {
				case 'Standard':
					$Return = $this->RunQuery($QueryType);
					break;
				case 'Prepared':
					$Return = $this->PrepareQuery();
					break;
			}
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\DBException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Query type: '
				. $QueryType . ' Run as: ' . $RunAs;
			$this->Debugging->WriteToLog($LogData);
		}

		return $Return;

		# end Query()
	}


	public function ShowTables()
	{
		$this->SQL = "\n"
			. "SHOW TABLES";

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__;
			$this->Debugging->WriteToLog($LogData);
		}

		if ($returnValue == \FALSE)
		{
			throw new \DBException('Commit failed.');
		}
	}


# end ShowTables()
}

/* end of final class MySQL_DB */