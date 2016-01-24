<?php

# MySQL_Query.class.php
# by Nicole Ward
# <http://snowwolfegames.com>
# <nikki@snowwolfegames.com>
#
# Copyright © 2009 - 2014 - SnowWolfe Games, LLC
# This file is part of DatabaseAbstractionLayer.
# This script handles mysql database interactions.
# properties:
# $DB_Con
# - protected
# - resource
# - hold the connection to the database
# $Debug
# - protected
# - boolean
# - flag if debugging is on/off
# $Debugging
# - protected
# - object
# - holds the logger object for recording debugging messages
# $Table
# - protected
# - string
# - holds the name of the current table
# $TableAlias
# - protected
# - string
# - holds the alias of the current table
# $JoinTables
# - protected
# - array
# - holds the tables for join queries
# $JoinType
# - protected
# - array
# - holds the types of joins being done
# $JoinTable
# - protected
# - array
# - holds the tables for join queries
# $JoinMethod
# - protected
# - array
# - holds the method the two tables will be joined by
# $JoinStatement
# - protected
# - array
# - holds the statement for joining the tables
# $LockTables
# - protected
# - array
# - holds the list of tables to be locked
# $Columns
# - protected
# - array
# - holds a list of columns
# $Where
# - protected
# - string
# - holds the where information
# $OrderBy
# - protected
# - string
# - holds order by field
# $OrderByDir
# - protected
# - string
# - holds order by direction
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
# $InsertValues
# - protected
# - array
# - holds the values for inserts
# $DuplicateKey
# - protected
# - array
# - holds values to update on duplicate key
# $Engine
# - protected
# - string
# - holds the engine type
# $SQL
# - protected
# - string
# - holds the complete sql query
#
# methods:
# __construct()
# -- parameters:
# -- $DB_Con
# 		- resource
# 		- holds the database connection handle
# __clone()
# __destruct()
# -- calls:
# - Logger::CloseLogFile()
# StartDebugging()
# - opens the debugging log up for use
# -- calls:
# 		- Logger::init()
# 		- Logger::OpenLogFile()
# ResetQuery()
# - Resets all the variables for making a query
# SetDebugging()
# - Sets the $Debug variable
# -- parameters:
# -- $Debug
# 		- boolean
# 		- sets debug on (\TRUE) or off (\FALSE)
# -- calls:
# 		- Sanitize()
# SetQuery()
# - Sets the $SQL variable
# -- parameters:
# -- $QueryType
# 		- string
# 		- holds the type of query to be created
# SetTable()
# - Sets the $Table variable
# -- parameters:
# -- $Table
# 		- string
# 		- holds the name of the table the query will be performed against
# -- $Alias
# 		- string
# 		- holds the alias to use for the table in the query
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetJoinTables()
# - Sets the $JoinTable, $JoinType, $JoinMethod, $JoinStatement variables
# -- parameters:
# -- $JoinTable
# 		- string
# 		- holds the name of tables for creating a join query
# -- $JoinType
# 		- string
# 		- holds the join type for a join query
# -- $JoinMethod
# 		- string
# 		- holds the way the tables will be joined
# -- $JoinStatement
# 		- string
# 		- holds the statement for joining the tables
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetLockTables()
# - Sets the $LockTables variable
# -- parameters:
# -- $LockTables
# 		- array
# 		- holds the names of the tables to be locked
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetColums()
# - Sets the $SelectColumns variable
# -- parameters:
# -- $Columns
# 		- array
# 		- holds the names of the columns to be selected
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetWhere()
# - Sets the $Where variable
# -- parameters:
# -- $Where
# 		- array
# 		- holds the information for creating a where clause
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetOrderBy()
# - Sets the $OrderBy variable
# -- parameters:
# -- $OrderBy
# 		- array
# 		- holds the order by field
# -- $Direction
# 		- array
# 		- holds the order by direction
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetGroupBy()
# - Sets the $GroupBy variable
# -- parameters:
# -- $GroupBy
# 		- string
# 		- holds the field to group by
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetLimit()
# - Sets the $Limit variable
# -- parameters:
# -- $Limit
# 		- integer
# 		- holds the limit number
# -- $Offset
# 		- integer
# 		- holds the offset number
# 		- defaults to null
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetInsertValues()
# - Sets the $InsertValues variable
# -- parameters:
# -- $Values
# 		- array
# 		- holds the values for an insert query
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetDuplicateKey()
# - Sets the $GroupBy variable
# -- parameters:
# -- $DuplicateKey
# 		- array
# 		- holds the field to update on duplicate key
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# SetEnginey()
# - Sets the $Engine variable
# -- parameters:
# -- $Engine
# 		- string
# 		- holds the engine to use
# - calls:
# - Sanitize()
# - Logger::WriteToLog
# GetSQL()
# - returns:
# - the $SQL variable
# BuildSelectQuery()
# - calls:
# - self::BuildSelectClause()
# - self::BuildTableClause()
# - self::BuildJoinClause()
# - self::BuildWhereCluase()
# - self::BuildOrderByClause()
# - self::BuildLimitClause()
# - Logger::WriteToLog
# BuildUpdateQuery()
# - calls:
# - self::BuildTableClause()
# - self::BuildUpdateClause()
# - self::BuildWhereClause()
# - self::BuildLimitClause()
# - Logger::WriteToLog
# BuildInsertQuery()
# - calls:
# - self::BuildTableClause()
# - self::BuildInsertClause()
# - self::BuildDuplicateKeyClause()
# - Logger::WriteToLog
# BuildDeleteQuery()
# - calls:
# - self::BuildDeleteClause()
# - self::BuildTableClause()
# - self::BuildJoinClause()
# - self::BuildWhereClause()
# - self::BuildLimitClause()
# - Logger::WriteToLog
# BuildAlterQuery()
# - calls:
# - self::BuildTableClause()
# - self::BuildAlterClause()
# - Logger::WriteToLog
# BuildTruncateQuery()
# - calls:
# - self::BuildTableClause()
# - Logger::WriteToLog
# BuildOptimizeQuery()
# calls:
# - Logger::WriteToLog
# BuildShowColumnQuery()
# - calls:
# - Logger::WriteToLog
# BuildShowTablesQuery()
# - calls:
# - Logger::WriteToLog
# BuildCreateTemporaryQuery()
# - calls:
# - self::BuildTableQuery()
# - self::BuildCreateTemporary()
# - self::BuildEngineClause()
# - Logger::WriteToLog
# BuildDropTemporaryQuery()
# - calls:
# - self::BuildTableClause()
# - Logger::WriteToLog
# BuildLockTablesQuery()
# - calls:
# - self::BuildLockTablesClause()
# - Logger::WriteToLog
# BuildUnlockTablesQuery()
# - calls:
# - Logger::WriteToLog
# BuildSelectClause()
# - calls:
# - Logger::WriteToLog
# BuildUpdateClause()
# - calls:
# - MySQL_DB::EscapeString()
# - Logger::WriteToLog
# BuildInsertClause()
# - calls:
# - Logger::WriteToLog
# BuildOnDuplicateKeyClause()
# - calls:
# - Logger::WriteToLog
# BuildDeleteClause()
# - calls:
# - Logger::WriteToLog
# BuildAlterClause()
# - calls:
# - Logger::WriteToLog
# BuildCreateTemporaryClause()
# - calls:
# - Logger::WriteToLog
# BuildTableClause()
# - calls:
# - Logger::WriteToLog
# BuildJoinClause()
# - calls:
# - Logger::WriteToLog
# BuildWhereClause()
# - calls:
# - Logger::WriteToLog
# BuildOrderByClause()
# - calls:
# - Logger::WriteToLog
# BuildLimitClause()
# - calls:
# - Logger::WriteToLog
# BuildLockTablesClause()
# - calls:
# - Logger::WriteToLog
# BuildEngineClause()
# - calls:
# - Logger::WriteToLog

namespace Fez;

if (0 > \version_compare(PHP_VERSION, '5'))
{
	throw new \Exception('This file was generated for PHP 5');
}

/**
 * include DBInterface
 *
 * @author Nicole Ward, <nikki@snowwolfegames.com>
 */
require_once('QueryInterface.class.php');

/* user defined includes */

/* user defined constants */

final class MySQL_Query extends QueryInterface
{
	# \InvalidArgumentException Codes

	const MISSING_DATA = 1;
	const WRONG_TYPE = 2;
	const WRONG_VALUE = 3;

	protected $DB_Con;
	protected $Debug = \FALSE;
	protected $Debugging = \NULL;
	protected $Table = \NULL;
	protected $TableAlias = \NULL;
	protected $JoinTables = \NULL;
	protected $JoinType = array();
	protected $JoinTable = array();
	protected $JoinMethod = array();
	protected $JoinStatement = array();
	protected $JoinAlias = array();
	protected $LockTables = \NULL;
	protected $Columns = array();
	protected $Where = array();
	protected $OrderBy = \NULL;
	protected $OrderByDir = \NULL;
	protected $GroupBy = \NULL;
	protected $Limit = \NULL;
	protected $Offset = \NULL;
	protected $InsertValues = \NULL;
	protected $DuplicateKey = \NULL;
	protected $Engine = \NULL;
	protected $SQL = \NULL;

	public function __construct($DB_Con)
	{
		$this->DB_Con = $DB_Con;

		# end __construct()
	}


	public function __clone()
	{
		\trigger_error('Clone is not allowed.', \E_USER_ERROR);

		# end __clone()
	}


	public function __destruct()
	{
		/* 				$this->Close();
		  if ($this->Debug == \TRUE)
		  {
		  $this->DB_Con->Debugging->CloseLogFile();
		  unset($this->DB_Con->Debugging);
		  } */

		# end __destruct()
	}


	public function StartDebugging()
	{
		/* 				$this->DB_Con->Debugging = new Logger();
		  $this->DB_Con->Debugging->init($FileName, $IncludeDate = \TRUE, $Priority = 'Medium', $LogType = 'Debugging', $Sendmail = \FALSE);
		  $this->DB_Con->Debugging->OpenLogFile(); */

		# end StartDebugging()
	}


	public function ResetQuery()
	{
		$this->Table = \NULL;
		$this->TableAlias = \NULL;
		$this->JoinTables = \NULL;
		$this->JoinType = array();
		$this->JoinTable = array();
		$this->JoinMethod = array();
		$this->JoinStatement = array();
		$this->JoinAlias = array();
		$this->LockTables = \NULL;
		$this->Columns = array();
		$this->Where = array();
		$this->OrderBy = \NULL;
		$this->OrderByDir = \NULL;
		$this->GroupBy = \NULL;
		$this->Limit = \NULL;
		$this->Offset = \NULL;
		$this->InsertValues = \NULL;
		$this->DuplicateKey = \NULL;
		$this->Engine = \NULL;
		$this->SQL = \NULL;
		# end ResetQuery()
	}


	public function SetDebug($Debug)
	{
		$this->Debug = Sanitize($Debug, 'boolean');

		if ($this->Debug === \TRUE)
		{
//						$this->StartDebugging();
		}

		# end SetDebug()
	}


	public function SetQuery($QueryType)
	{
		switch ($QueryType) {
			case 'SELECT':
				try {
					$this->BuildSelectQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'DELETE':
				try {
					$this->BuildDeleteQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'UPDATE':
				try {
					$this->BuildUpdateQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'INSERT':
				try {
					$this->BuildInsertQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'ALTER TABLE':
				try {
					$this->BuildAlterQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'TRUNCATE TABLE':
				try {
					$this->BuildTruncateQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'OPTIMIZE TABLE':
				try {
					$this->BuildOptimizeQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'SHOW COLUMNS':
				try {
					$this->BuildShowColumnsQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'SHOW TABLES':
				try {
					$this->BuildShowTablesQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'CREATE TEMPORARY TABLE':
				try {
					$this->BuildCreateTemporaryQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'DROP TEMPORARY TABLE':
				try {
					$this->BuildDropTemporaryQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'LOCK TABLES':
				try {
					$this->BuildLockTablesQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			case 'UNLOCK TABLES':
				try {
					$this->BuildUnlockTablesQuery();
				} catch (\InvalidArgumentException $exc) {
					throw $exc;
				} catch (\Exception $exc) {
					throw $exc;
				}
				break;
			default:
				throw new \DBException('Invalid query type.');
				break;
		}

		# end SetQuery()
	}


	public function SetTable($Table, $Alias = \NULL)
	{
		$this->Table = Sanitize($Table, 'string');
		if (isset($Alias))
		{
			$this->TableAlias = Sanitize($Alias, 'string');
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Table: ' . $this->Table;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetTable()
	}


	public function SetJoinTables($JoinTables)
	{
		$this->JoinTables = Sanitize($JoinTables, 'string');
		foreach ($this->JoinTables AS $Value)
		{
			if (empty($Value['JoinAlias']))
			{
				$Value['JoinAlias'] = \NULL;
			}
			$this->SetJoinTable($Value['JoinType'], $Value['JoinTable'], $Value['JoinMethod'], $Value['JoinStatement'], $Value['JoinAlias']);
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Tables: ' . \var_export($this->JoinTables, \TRUE);
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetJoinTables()
	}


	public function SetJoinTable($JoinType, $JoinTable, $JoinMethod, $JoinStatement, $JoinAlias = \NULL)
	{
		$this->JoinType[] = Sanitize($JoinType, 'string');
		$this->JoinTable[] = Sanitize($JoinTable, 'string');
		if (!empty($JoinAlias))
		{
			$this->JoinAlias[] = Sanitize($JoinAlias, 'string');
		}
		$this->JoinMethod[] = Sanitize($JoinMethod, 'string');
		$this->JoinStatement[] = Sanitize($JoinStatement, 'string');

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Join tables: ' . $this->JoinTablesClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetJoinTable()
	}


	public function SetLockTables(Array $LockTables)
	{
		$this->LockTables = Sanitize($LockTables, 'string');
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Lock tables: ' . \var_export($this->LockTables, \true);
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetWhere()
	}


	public function SetColumns(Array $Columns)
	{
		$this->Columns = $Columns;
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Select columns: ' . \var_export($this->Columns, \true);
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetColumns()
	}


	public function SetWhere(Array $Where)
	{
		foreach ($Where as $Value)
		{
			if (!empty($Value['SecondOperand']))
			{
				$Value['SecondOperand'] = Sanitize($Value['SecondOperand']);
			}
		}
		$this->Where = $Where;
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Where arguments: ' . \var_export($this->Where, \true);
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetWhere()
	}


	public function SetOrderBy($OrderBy)
	{
		$this->OrderBy = Sanitize($OrderBy, 'string');

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Order By: '
				. var_export($this->OrderBy, \TRUE);
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetOrderBy()
	}


	public function SetGroupBy($GroupBy)
	{
		$this->GroupBy = Sanitize($GroupBy, 'string');

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Group By: ' . $this->GroupBy;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetGroupBy()
	}


	public function SetLimit($Limit, $Offset = \NULL)
	{
		$Limit = Sanitize($Limit, 'int');
		if ($Limit !== \FALSE)
		{
			$this->Limit = $Limit;
		} else
		{
			throw new \DBException("Invalid limit value.");
		}
		if (isset($Offset))
		{
			$Offset = Sanitize($Offset, 'int');
			if ($Offset !== \FALSE)
			{
				$this->Offset = $Offset;
			} else
			{
				throw new \DBException("Invalid offset value.");
			}
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Limit: ' . $this->Limit;
			$LogData .= (!empty($this->Offset))
				? ' Offset: ' . $this->Offset
				: ' No offset.';
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetLimit()
	}


	public function SetInsertValues(Array $Values)
	{
		$this->InsertValues = $Values;

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Values: '
				. $this->InsertValues;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetValues()
	}


	public function SetDuplicateKey(array $DuplicateKey)
	{
		$this->DuplicateKey = Sanitize($DuplicateKey, 'string');

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Duplicate Key: ' . $this->DuplicateKey;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetDuplicateKey()
	}


	public function SetEngine($Engine)
	{
		$this->Engine = Sanitize($Engine, 'string');

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Engine: ' . $this->Engine;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end SetEngine()
	}


	public function GetSQL()
	{
		return $this->SQL;

		# end GetSQL()
	}


	public function BuildSelectQuery()
	{
		$this->SQL = "SELECT ";
		$this->SQL .= $this->BuildSelectClause();
		try {
			$this->SQL .= $this->BuildTableClause('SELECT');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		if (!empty($this->JoinTables))
		{
			$this->SQL .= $this->BuildJoinClause();
		}
		if (!empty($this->Where))
		{
			$this->SQL .= $this->BuildWhereClause();
		}
		if (!empty($this->GroupBy))
		{
			$this->SQL .= ' GROUP BY ';
			foreach ($this->GroupBy as $Value)
			{
				$this->SQL .= $Value . ", ";
			}
			$this->SQL = \rtrim($this->SQL, ", ");
		}
		if (!empty($this->OrderBy))
		{
			$this->SQL .= $this->BuildOrderByClause();
		}
		if (!empty($this->Limit))
		{
			$this->SQL .= $this->BuildLimitClause();
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildSelectQuery()
	}


	public function BuildUpdateQuery()
	{
		$this->SQL = "UPDATE ";
		try {
			$this->SQL .= $this->BuildTableClause('UPDATE');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		$this->SQL .= $this->BuildUpdateClause();
		if (!empty($this->Where))
		{
			$this->SQL .= $this->BuildWhereClause();
		}
		if (!empty($this->Limit))
		{
			$this->SQL .= $this->BuildLimitClause();
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildUpdateQuery()
	}


	public function BuildInsertQuery()
	{
		# if $this->OnDuplicateKey is an array, update those fields
		# else update = "(INSERT IGNORE)"
		$this->SQL = "INSERT ";
		if ($this->DuplicateKey === 'IGNORE')
		{
			$this->SQL .= 'IGNORE';
		}
		try {
			$this->SQL .= $this->BuildTableClause('INSERT');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		$this->SQL .= $this->BuildInsertClause();
		if (\is_array($this->DuplicateKey))
		{
			$this->SQL .= $this->BuildOnDuplicateKeyClause();
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildInsertQuery()
	}


	public function BuildDeleteQuery()
	{
		$this->SQL = "DELETE ";
		$this->SQL .= $this->BuildDeleteClause();
		try {
			$this->SQL .= $this->BuildTableClause('DELETE');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		if (!empty($this->JoinTables))
		{
			$this->SQL .= $this->BuildJoinClause();
		}
		if (!empty($this->Where))
		{
			$this->SQL .= $this->BuildWhereClause();
		}
		if (!empty($this->Limit))
		{
			$this->SQL .= $this->BuildLimitClause();
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildDeleteQuery()
	}


	public function BuildAlterQuery()
	{
		$this->SQL = "ALTER TABLE ";
		try {
			$this->SQL .= $this->BuildTableClause('ALTER');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		$this->SQL .= $this->BuildAlterClause();
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildTruncateQuery()
	}


	public function BuildTruncateQuery()
	{
		$this->SQL = "TRUNCATE TABLE ";
		try {
			$this->SQL .= $this->BuildTableClause('TRUNCATE');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildTruncateQuery()
	}


	public function BuildOptimizeQuery()
	{
		$this->SQL = "OPTIMIZE TABLE ";
		try {
			$this->SQL .= $this->BuildTableClause('OPTIMIZE');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildOptimizeQuery()
	}


	public function BuildShowColumnsQuery()
	{
		$this->SQL = "SHOW COLUMNS FROM ";
		try {
			$this->SQL .= $this->BuildTableClause('SHOW_COLUMNS');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildShowColumnsQuery()
	}


	public function BuildShowTablesQuery()
	{
		$this->SQL = "SHOW TABLES";
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildShowTablesQuery()
	}


	public function BuildCreateTemporaryQuery()
	{
		$this->SQL = "CREATE TEMPORARY TABLE IF NOT EXISTS ";
		try {
			$this->SQL .= $this->BuildTableClause('CREATE_TEMPORARY');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}
		$this->SQL .= $this->BuildCreateTemporaryClause();
		$this->SQL .= $this->BuildEngineClause();
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildCreateTemporaryQuery()
	}


	public function BuildDropTemporaryQuery()
	{
		$this->SQL = "DROP TEMPORARY TABLE IF EXISTS ";
		try {
			$this->SQL .= $this->BuildTableClause('DROP_TEMPORARY');
		} catch (\InvalidArgumentException $exc) {
			throw $exc;
		} catch (\Exception $exc) {
			throw $exc;
		}

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildDropTemporaryQuery()
	}


	public function BuildLockTablesQuery()
	{
		$this->SQL = "LOCK TABLES ";
		$this->SQL .= $this->BuildLockTablesClause();
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildLockTablesQuery()
	}


	public function BuildUnlockTablesQuery()
	{
		$this->SQL = "UNLOCK TABLES ";
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' SQL: ' . $this->SQL;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}

		# end BuildUnlockTablesQuery()
	}


	protected function BuildSelectClause()
	{
		$SelectClause = \NULL;
		$TrimmedSelectClause = \NULL;

		foreach ($this->Columns as $FieldArray)
		{
			if (!empty($FieldArray['SQLFunction']))
			{
				$SelectClause .= $FieldArray['SQLFunction'] . "(";
				if (!empty($FieldArray['Distinct']))
				{
					$SelectClause .= "DISTINCT ";
				}
				if (!empty($FieldArray['If']))
				{
					$SelectClause .= "IF(";
					$SelectClause .= (!empty($FieldArray['Table']))
						? '`' . $FieldArray['Table'] . '`.'
						: '';
					$SelectClause .= "`" . $FieldArray['Field'] . "` = " . $FieldArray['If'] . "))";
				} else
				{
					$SelectClause .= (!empty($FieldArray['Table']))
						? '`' . $FieldArray['Table'] . '`.'
						: '';
					$SelectClause .= '`' . $FieldArray['Field'] . '`';
					if (isset($FieldArray['FormatLevel']))
					{
						$SelectClause .= ', ' . $FieldArray['FormatLevel']
							. ')';
					} elseif (!empty($FieldArray['CastType']))
					{
						$SelectClause .= ' AS ' . $FieldArray['CastType']
							. ')';
					} else
					{
						$SelectClause .= ')';
					}
				}
			} else
			{
				if (!empty($FieldArray['Distinct']))
				{
					$SelectClause .= "DISTINCT ";
				}
				$SelectClause .= (!empty($FieldArray['Table']))
					? '`' . $FieldArray['Table'] . '`.'
					: '';
				$SelectClause .= ($FieldArray['Field'] === '*')
					? $FieldArray['Field']
					: '`' . $FieldArray['Field'] . '`';
			}

			if (!empty($FieldArray['Maths']))
			{
				$SelectClause .= " " . $FieldArray['Maths'];
			}

			if (!empty($FieldArray['ReturnAs']))
			{
				$SelectClause .= " AS " . $FieldArray['ReturnAs'] . ", ";
			} else
			{
				$SelectClause .= ", ";
			}
		}
		$TrimmedSelectClause = \rtrim($SelectClause, ", ");
		$TrimmedSelectClause .= ' ';

		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Built select clause. '
				. $TrimmedSelectClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $TrimmedSelectClause;

		# end BuildSelectClause()
	}


	protected function BuildUpdateClause()
	{
		$TrimmedUpdateClause = \NULL;
		$UpdateClause = 'SET ';

		foreach ($this->Columns as $FieldArray)
		{
			$UpdateClause .= (!empty($FieldArray['Table']))
				? '`' . $FieldArray['Table'] . '`.'
				: '';
			$UpdateClause .= '`' . $FieldArray['Field'] . "` = ";
			if (isset($FieldArray['Value']))
			{
				$Value = $this->DB_Con->EscapeString($FieldArray['Value']);
				if (\is_numeric($Value) ||
					\is_bool($Value) ||
					!empty($FieldArray['isSQLFunction']))
				{
					$UpdateClause .= $Value . ', ';
				} else
				{
					$UpdateClause .= "'" . $Value . "', ";
				}
			} elseif (!empty($FieldArray['If']))
			{
				$UpdateClause .= 'IF((`' . $FieldArray['Field'] . '` ' . $FieldArray['Maths'] . ') ' . $FieldArray['ComparisonOperator'] . ' ' . $FieldArray['ComparisonValue'] . ', ' . $FieldArray['ComparisonValue'] . ', (`' . $FieldArray['Field'] . '` ' . $FieldArray['Maths'] . ')), ';
			} elseif (!empty($FieldArray['Maths']))
			{
				$UpdateClause .= ' `' . $FieldArray['Field'] . '` ' . $FieldArray['Maths'] . ', ';
			} else
			{
				$UpdateClause .= "?, ";
			}
		}
		$TrimmedUpdateClause = \rtrim($UpdateClause, ", ");
		$TrimmedUpdateClause .= ' ';
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Update clause: '
				. $TrimmedUpdateClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $TrimmedUpdateClause;

		# end BuildSelectClause()
	}


	protected function BuildInsertClause()
	{
		$Fields = '(';
		$TrimmedFields = \NULL;
		$InsValues = \NULL;
		$TrimmedValues = \NULL;
		$AllValues = \NULL;
		$TrimmedAllValues = \NULL;

		foreach ($this->Columns as $FieldArray)
		{
			$Fields .= '`' . $FieldArray['Field'] . '`, ';
		}
		$TrimmedFields = \rtrim($Fields, ", ");
		$TrimmedFields .= ')';
		if (isset($this->InsertValues))
		{
			foreach ($this->InsertValues as $Values)
			{
				foreach ($Values as $Value)
				{
					if (\is_numeric($Value['Value']))
					{
						$InsValues .= $this->DB_Con->EscapeString($Value['Value']) . ', ';
					} else
					{
						$InsValues .= "'" . $this->DB_Con->EscapeString($Value['Value']) . "', ";
					}
				}
				$TrimmedValues = \rtrim($InsValues, ", ");
				$AllValues .= '(' . $TrimmedValues . '),' . PHP_EOL;
				unset($InsValues, $TrimmedValues);
			}
			$TrimmedAllValues = \rtrim($AllValues, ',' . PHP_EOL);
		} else
		{
			foreach ($this->Columns as $FieldArray)
			{
				$InsValues .= '?, ';
			}
			$TrimmedValues = \rtrim($InsValues, ", ");
			$TrimmedAllValues = '(' . $TrimmedValues . ')';
		}

		$InsertClause = $TrimmedFields;
		if (!empty($TrimmedAllValues))
		{
			$InsertClause .= ' VALUES ' . $TrimmedAllValues;
		}
		$InsertClause .= ' ';
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Insert clause: '
				. $InsertClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $InsertClause;

		# end BuildInsertClause()
	}


	protected function BuildOnDuplicateKeyClause()
	{
		$TrimmedDuplicateKeyClause = \NULL;
		$DuplicateKeyClause .= "\n"
			. "ON DUPLICATE KEY UPDATE ";

		foreach ($this->DuplicateKey as $FieldArray)
		{
			$DuplicateKeyClause .= $FieldArray['Field'] . " =";
			if (!empty($FieldArray['Maths']))
			{
				$DuplicateKeyClause .= $FieldArray['Field'] . $FieldArray['Maths'] . " VALUES(" . $FieldArray['Field'] . "), ";
			} else
			{
				$DuplicateKeyClause .= " VALUES(" . $FieldArray['Field'] . "), ";
			}
		}
		$TrimmedDuplicateKeyClause = \rtrim($DuplicateKeyClause, ", ");
		$TrimmedDuplicateKeyClause .= ' ';
		if ($this->Debug == \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Duplicate key clause: ' . $TrimmedDuplicateKeyClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $TrimmedDuplicateKeyClause;

		# end BuildOnDuplicateKeyClause()
	}


	protected function BuildDeleteClause()
	{
		if ($this->Debug == \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Delete clause: ' . $DeleteClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $DeleteClause;

		# end BuildSelectClause()
	}


	protected function BuildAlterClause()
	{
		$AlterClause = \NULL;
		$TrimmedAlterClause = \NULL;
		foreach ($this->QueryArr['Action'] as $Action)
		{
			switch ($Action) {
				case 'Index':
					foreach ($this->QueryArr['Index'] as $Index)
					{
						$AlterClause .= "\n"
							. "ADD INDEX (" . $Index . "), ";
					}
					break;
				case 'Unique':
					$AlterClause .= "\n"
						. "ADD UNIQUE ";
					foreach ($this->QueryArr['UniqueIndex'] as $Index)
					{
						$AlterClause .= $Index . ", ";
					}
					break;
				case 'Primary':
					$AlterClause .= "\n"
						. "ADD PRIMARY KEY ";
					foreach ($this->QueryArr['UniqueIndex'] as $Index)
					{
						$AlterClause .= $Index . ", ";
					}
					break;
			}
		}

		$TrimmedAlterClause = \rtrim($AlterClause, ", ");
		$TrimmedAlterClause .= ' ';
# need to add the rest of these http://dev.mysql.com/doc/refman/5.1/en/alter-table.html

		return $TrimmedAlterClause;

		# end BuildAlterClause()
	}


	protected function BuildCreateTemporaryClause()
	{
		$CreateTemporaryClause = "(";
		foreach ($this->Columns as $FieldArray)
		{
			$CreateTemporaryClause .= $FieldArray['Field'] . ", ";
		}
		$CreateTemporaryClause = \rtrim($CreateTemporaryClause, ", ");
		$CreateTemporaryClause .= ") ";

		return $CreateTemporaryClause;

		# end BuildCreateTemporaryClause()
	}


	protected function BuildTableClause($QueryType)
	{
		switch ($QueryType) {
			case 'SELECT':
			case 'DELETE':
				$TableClause = 'FROM `' . $this->Table . '` ';
				break;
			case 'INSERT':
				$TableClause = 'INTO `' . $this->Table . '` ';
				break;
			case 'UPDATE':
			case 'ALTER':
			case 'TRUNCATE':
			case 'OPTIMIZE':
			case 'SHOW_COLUMNS':
			case 'CREATE_TEMPORARY':
				$TableClause = '`' . $this->Table . '` ';
				break;
			default:
				throw new \InvalidArgumentException("Invalid query type.", self::WRONG_VALUE);
				break;
		}

		if (!empty($this->TableAlias))
		{
			$TableClause .= 'AS ' . $this->TableAlias . ' ';
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Table clause: '
				. $TableClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $TableClause;

		# end BuildTableClause()
	}


	protected function BuildJoinClause()
	{
		$JoinTablesClause = '';
		foreach ($this->JoinType AS $Key => $Value)
		{
			$JoinTablesClause .= $Value . ' JOIN `' . $this->JoinTable[$Key] . '` ';
			if (!empty($this->JoinAlias[$Key]))
			{
				$JoinTablesClause .= 'AS ' . $this->JoinAlias[$Key] . ' ';
			}
			$JoinTablesClause .= $this->JoinMethod[$Key] . ' ' . $this->JoinStatement[$Key];
		}
		$JoinTablesClause .= ' ';
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Join tables clause: '
				. $JoinTablesClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $JoinTablesClause;

		# end BuildJoinClause()
	}


	protected function BuildWhereClause()
	{
		$WhereClause = 'WHERE ';
		foreach ($this->Where as $Arguments)
		{
			$WhereClause .= (!empty($Arguments['ClauseOperator']))
				? $Arguments['ClauseOperator'] . ' '
				: '';
			$WhereClause .= (!empty($Arguments['FirstOperandTable']))
				? '`' . $Arguments['FirstOperandTable'] . '`.'
				: '';
			$WhereClause .= (empty($Arguments['isNotAField_First']))
				? '`' . $Arguments['FirstOperand'] . '` '
				: $Arguments['FirstOperand'] . ' ';
			$WhereClause .= $Arguments['ExpressionOperator'] . ' ';
			$WhereClause .= (!empty($Arguments['SecondOperandTable']))
				? '`' . $Arguments['SecondOperandTable'] . '`.'
				: '';
			$WhereClause .= (!empty($Arguments['SecondOperand']))
				? ((!empty($Arguments['isAField_Second']))
					? "`" . $this->DB_Con->EscapeString($Arguments['SecondOperand']) . "`"
					: ((\is_numeric($Arguments['SecondOperand']) ||
					\is_bool($Arguments['SecondOperand']))
						? $this->DB_Con->EscapeString($Arguments['SecondOperand'])
						: "'" . $this->DB_Con->EscapeString($Arguments['SecondOperand']) . "'"))
				: ((\is_numeric($Arguments['SecondOperand']) ||
				\is_bool($Arguments['SecondOperand']))
					? $this->DB_Con->EscapeString($Arguments['SecondOperand'])
					: '?');
			$WhereClause .= ' ';
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Where: ' . $WhereClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $WhereClause;

		# end BuildWhereClause()
	}


	protected function BuildOrderByClause()
	{
		if (!empty($this->OrderBy))
		{
			$OrderByClause = 'ORDER BY ';
			foreach ($this->OrderBy as $OrderBy)
			{
				$OrderByClause .= $OrderBy['Column'] . " ";
				if (!empty($OrderBy['Direction']))
				{
					$OrderByClause .= $OrderBy['Direction'] . ", ";
				} else
				{
					$OrderByClause .= ", ";
				}
			}

			$OrderByClause = \rtrim($OrderByClause, ", ");
			$OrderByClause .= " ";
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Order By: ' . $OrderByClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $OrderByClause;

		# end BuildOrderByClause()
	}


	protected function BuildLimitClause()
	{
		$LimitClause = \NULL;
		if (!empty($this->Limit))
		{
			$LimitClause = "LIMIT ";
			if (isset($this->Offset))
			{
				$LimitClause .= $this->Offset . ', ';
			}
			$LimitClause .= $this->Limit . ' ';
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Limit: ' . $LimitClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $LimitClause;

		# end BuildLimitClause()
	}


	protected function BuildLockTablesClause()
	{
		$LockTablesClause = \NULL;
		if (!empty($this->LockTables))
		{
			foreach ($this->LockTables as $LocksArray)
			{
				$LockTablesClause .= $LocksArray['Table'] . " ";
				if (!empty($LocksArray['TableAlias']))
				{
					$LockTablesClause .= "AS " . $LocksArray['TableAlias'] . " ";
				}
				$LockTablesClause .= $LocksArray['LockType'] . ", ";
			}

			$LockTablesClause = \rtrim($LockTablesClause, ", ");
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Lock tables: ' . $LockTablesClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $LockTablesClause;

		# end BuildLockTablesClause()
	}


	protected function BuildEngineClause()
	{
		$EngineClause = \NULL;

		if (isset($this->Engine))
		{
			$EngineClause = " ENGINE = " . $this->QueryArr['Engine'];
		}
		if ($this->Debug === \TRUE)
		{
			$LogData = __FILE__ . ' ' . __METHOD__ . ' Engine: ' . $EngineClause;
			$this->DB_Con->Debugging->WriteToLog($LogData);
		}
		return $EngineClause;

		# end BuildEngineClause()
	}


}

# end MySQL_Query class