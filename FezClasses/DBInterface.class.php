<?php

# DBInterface.class.php
# by Nicole Ward
# <http://snowwolfegames.com>
# <nikki@snowwolfegames.com>
#
# Copyright � 2009 - 2014 - SnowWolfe Games, LLC
# This file is part of DatabaseAbstractionLayer.
# This script sets the interface for the DB controls
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
# 		- sets debug on (TRUE) or off (FALSE)
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
# select_db() - changes db
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

if (0 > version_compare(PHP_VERSION, '5'))
{
	throw new Exception('This file was generated for PHP 5');
}

/* user defined includes */

/* user defined constants */

abstract class DBInterface
{

	abstract public function __construct();

	abstract public function __clone();

	abstract public function __destruct();

	abstract public function SetDebug($Debug);

	abstract public function SetTable($Table, $Alias = NULL);

	abstract public function SetJoinTables(Array $Tables);

	abstract public function SetLockTables(Array $LockTables);

	abstract public function SetColumns(Array $Columns);

	abstract public function SetWhere(Array $Where);

	abstract public function SetOrderBy($OrderBy, $Direction = NULL);

	abstract public function SetGroupBy($GroupBy);

	abstract public function SetLimit($Limit, $Offset = NULL);

	abstract public function SetInsertValues(Array $Values);

	abstract public function SetDuplicateKey($DuplicateKey);

	abstract public function SetEngine($Engine);

	abstract protected function GetParamType($Value);

	abstract protected function StartDebugging();

	abstract public function Connect();

	abstract public function Connected();

	abstract public function Close();

	abstract public function SelectDB($Database);

	abstract public function ErrorNo();

	abstract public function Error();

	abstract public function EscapeString($String);

	abstract public function InsertID();

	abstract public function AutoCommit($Setting);

	abstract public function StartTrans();

	abstract public function RollbackTrans();

	abstract public function CommitTrans();

	abstract protected function PrepareQuery();

	abstract public function BindInputParams();

	abstract public function ExecutePreparedQuery();

	abstract public function FetchPreparedResults($ReturnFormat = 'assoc');

	abstract protected function StmtBindArray(&$ReturnArr);

	abstract protected function StmtBindAssoc(&$ReturnArr);

	abstract protected function StmtBindObject(&$ReturnObj);

	abstract public function CloseStmt();

	abstract protected function RunQuery($QueryType);

	abstract public function FetchResults($Result, $ReturnFormat = 'assoc', $ReturnArrFormat = 'MYSQLI_NUM');

	abstract public function CloseResult($Result);

	abstract public function NumRows($Result = NULL);

	abstract public function AffectedRows();

	abstract public function Query($QueryType = NULL, $RunAs = 'Standard');

	abstract public function ShowTables();
}

# end of interface DBInterface
?>