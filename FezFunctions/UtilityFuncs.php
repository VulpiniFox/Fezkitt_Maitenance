<?php

# UtilityFuncs.php
# by Nicole Ward
# <http://snowwolfegames.com>
#
# Copyright (c) 2008 - 2014 - SnowWolfe Games, LLC
#
# this script contains basic utility functions
# ChModMyFile() sets a file to 0766
# MyErrorHandler() custom error handler
# bb2html my custom bb code to html converter
# FoalMaxValue cuts the value to a flat 8.00 if it is higher
# GetRandomColor returns a random hex color code
# stmt_bind_assoc returns an assoc array for prepared statements
# stmt_bind_object returns an object for prepared statements
# VerifyFormKey checks the HMAC keys on forms submitted to E-R for validity to prevent tampering

namespace Fez;

function ChModMyFile($TheFile)
{
	if (\function_exists('posix_getuid'))
	{
		if (\substr(\sprintf('%o', \fileperms($TheFile)), -4) != 0766)
		{
			if (\fileowner($TheFile) == \posix_getuid())
			{
				\chmod($TheFile, 0766);
			}
		}
	}

	# end ChModMyFile()
}


function MyErrorHandler($ErrorNum, $ErrorMsg, $ErrorFile, $ErrorLine, $ErrorContext)
{
	switch ($ErrorNum) {
		case E_ERROR: //do something
		case E_WARNING: //do something
		case E_NOTICE: //do something
		case E_PARSE: //do something
			return false;
			break;
		case E_USER_ERROR: //do something
			$Error = "\n\r\r\n" . DATE_TODAY . " " . TIME_NOW . "\r\n";
			$Error .= "ERROR [#" . $ErrorNum . "] " . $ErrorMsg . "\r\n";
			$Error .= "Fatal error encountered on line " . $ErrorLine . " in file " . $ErrorFile . "\r\n";

			//$Error .= print_r($ErrorContext, 1)."\n\r\r\n";

			\error_log($Error, 3, ERROR_LOG . DATE_TODAY . "-errors.log");
			ChModMyFile(ERROR_LOG . DATE_TODAY . "-errors.log");

			break;
		case E_USER_WARNING: //do something
			$Error = DATE_TODAY . " " . TIME_NOW . "\r\n";
			$Error .= "WARNING [#" . $ErrorNum . "] " . $ErrorMsg . "\r\n";
			$Error .= "Error encountered on line " . $ErrorLine . " in file " . $ErrorFile . "\r\n";

			\error_log($Error, 3, ERROR_LOG . DATE_TODAY . "-errors.log");
			ChModMyFile(ERROR_LOG . DATE_TODAY . "-errors.log");

			break;
		case E_USER_NOTICE: //do something
			$Error = DATE_TODAY . " " . TIME_NOW . "\r\n";
			$Error .= "NOTICE [#" . $ErrorNum . "] " . $ErrorMsg . "\r\n";
			$Error .= "Problem encountered on line " . $ErrorLine . " in file " . $ErrorFile . "\r\n";

			\error_log($Error, 3, ERROR_LOG . DATE_TODAY . "-errors.log");
			ChModMyFile(ERROR_LOG . DATE_TODAY . "-errors.log");

			break;
		default: //do something;
			$Error = DATE_TODAY . " " . TIME_NOW . "\r\n";
			$Error .= "DEFAULT WARNING [#" . $ErrorNum . "] " . $ErrorMsg . "\r\n";
			$Error .= "Error encountered on line " . $ErrorLine . " in file " . $ErrorFile . "\r\n";

			\error_log($Error, 3, ERROR_LOG . DATE_TODAY . "-errors.log");
			ChModMyFile(ERROR_LOG . DATE_TODAY . "-errors.log");
			break;
	}
	return true;

	# end MyErrorHandler()
}


function HandleError($LogData, $UserMsg = \NULL, $Location = \NULL)
{
	$Logger = new Logger();
	$Logger->init('error', \TRUE, 'High', 'Error', \FALSE)
		->SetFilePath(ERROR_LOG)
		->OpenLogFile()
		->WriteToLog($LogData, \TRUE);
	$Logger->CloseLogFile();

	if (!empty($Location))
	{
		LocationHeader($UserMsg, $Location, 'ErrorMsg');
	}

	# end HandleError()
}


function SetUserMsg($UserMsg, $MsgType = 'ErrorMsg')
{
	# MsgTypes - ErrorMsg, SuccessMsg
	$_SESSION[$MsgType] = $UserMsg;

	# end SetUserMsg()
}


function LocationHeader($UserMsg, $Location = 'error.php', $MsgType = 'ErrorMsg')
{
	SetUserMsg($UserMsg, $MsgType);
	\header("Location: " . URL . $Location);
	exit();

	# end ErrorHeader()
}


function EncodeData($Data)
{
	$ReturnValue = NULL;

	if (is_array($Data))
	{
		$ReturnValue = \base64_encode(serialize($Data));
	} else
	{
		$ReturnValue = \base64_encode($Data);
	}

	return $ReturnValue;

	# end EncodeData()
}


function GenerateFormKey($EncodedData)
{
	return \hash_hmac('ripemd160', $EncodedData, FORMKEY);

	# end GenerateFormKey()
}


function VerifyFormKey($FormKeySent, $EncodedData, $Debug = \FALSE)
{
	if (\hash_hmac('ripemd160', $EncodedData, FORMKEY) === $FormKeySent)
	{
		return \TRUE;
	} else
	{
		if ($Debug == \TRUE)
		{
			echo 'Form key received: ' . $FormKeySent . ' - Comparison: ' . \hash_hmac('ripemd160', $EncodedData, FORMKEY);
		}
		return \FALSE;
	}

	# end VerifyFormKey()
}


function bb2html($text)
{
	$patterns = array(
		"<",
		">",
		"[link=",
		"[/link]",
		"[b]",
		"[i]",
		"[u]",
		"[/b]",
		"[/i]",
		"[/u]",
		"[list]",
		"[/list]",
		"[listitem]",
		"[/listitem]",
		"[br]",
		"[hr]",
		"[color=",
		"[/color]",
		"[size=",
		"[/size]",
		"[c]",
		"[/c]",
		"[s]",
		"[/s]",
		"]"
	);
	$replacements = array(
		"&lt;",
		"&gt;",
		'<a href="',
		"</a>",
		"<b>",
		"<i>",
		"<u>",
		"</b>",
		"</i>",
		"</u>",
		"<ul>",
		"</ul>",
		"<li>",
		"</li>",
		"<br />",
		"<hr />",
		'<span class="',
		"</span>",
		'<span class="',
		"</span>",
		'<div class="center">',
		"</div>",
		"<strike>",
		"</strike>",
		'">'
	);
	$newText = \str_ireplace($patterns, $replacements, $text);
	$newText = \nl2br($newText); //second pass
	return $newText;

	# end bb2html()
}


function getRandomColor()
{
	return \substr('00000' . \dechex(\mt_rand(0, 0xffffff)), -6);

	# end GetRandomColor()
}


function OptimizeDB()
{
	# optimize the tables. Do all on Sundays, only heavily
	# used tables the rest of the week

	$Debug = \FALSE;

	# get a db connection
	$DB = new DatabaseFunctions();
	if (!$DB->connect())
	{
		exit();
	}

	if (date("D") == 'Sun')
	{
		$DB->ShowTables();
		if ($result = $DB->RunQuery())
		{
			while ($row = $DB->FetchResults($result, 'assoc'))
			{
				foreach ($row as $db => $TableName)
				{
					# create the query
					$QueryArr = array();
					$QueryArr['Debug'] = $Debug;
					$QueryArr['Table'] = $TableName;
					$DB->OptimizeTable($QueryArr);
					# figure out what to do if Query is false
				}
			}
			$DB->CloseResult($result);
		}
	} else
	{
		$Tables = array(
			'Ads',
			'BreedingRecords',
			'CryoBank',
			'CryoBankSales',
			'PrivateSales',
			'SemenOffers',
			'bids',
			'foreman',
			'hired_hands',
			'horsenotes',
			'notices',
			'offers',
			'ppl_online',
			'ranchnotes',
			'stud',
			'train_foal',
			'train_green',
			'transactions',
			'UserInfo',
			'UserRanchDescrip');

		foreach ($Tables as $TableName)
		{
			# create the query
			$QueryArr = array();
			$QueryArr['Debug'] = $Debug;
			$QueryArr['Table'] = $TableName;
			$DB->OptimizeTable($QueryArr);
			# figure out what to do if Query is false
		}
	}

	# end OptimizeDB()
}


function TrackAdmins($DB_Con, $PlayerID, $AnimalID, $Action, $Reason, $AdminID = 9999999)
{
	# default the Admin ID to 9999999 for automated processing.
	# Highly doubt we'll hit 10 million players
	$Columns = [
		[
			'Field' => 'AdminID'
		],
		[
			'Field' => 'PlayerID'
		],
		[
			'Field' => 'AnimalID'
		],
		[
			'Field' => 'Action'
		],
		[
			'Field' => 'Reason'
		]
	];
	$Params = [
		$AdminID,
		$PlayerID,
		$AnimalID,
		$Action,
		$Reason
	];

	try {
		$DB_Con->SetTable('TrackAdmins')
			->SetColumns($Columns)
			->SetInputParams($Params);
		$DB_Con->Query('INSERT', 'Prepared');
		$DB_Con->BindInputParams()
			->ExecutePreparedQuery();
	} catch (DBException $e) {
		throw $e;
		$ExecuteCheck = FALSE;
	} catch (Exception $e) {
		throw $e;
		$ExecuteCheck = FALSE;
	} finally {
		$DB_Con->CloseStmt()->ResetQuery();
	}

	return $ExecuteCheck;

	# end TrackAdmins()
}


function ValidateReferrer()
{
	if (isset($_SERVER['HTTP_REFERER']))
	{
		$ref = \filter_input(\INPUT_SERVER, 'HTTP_REFERER', \FILTER_SANITIZE_STRING);
	} else
	{
		$ref = \NULL;
	}

	if (\strpos(SRVR_ROOT, 'advanced') !== \FALSE)
	{
		$RefMatch = 'equine-ranch';
	} elseif (\strpos(SRVR_ROOT, 'unutsi88') !== \FALSE)
	{
		$RefMatch = 'snowwolfegames';
	} elseif (\strpos(SRVR_ROOT, 'sandbox1') !== \FALSE)
	{
		$RefMatch = 'siber-ized';
	} else
	{
		$RefMatch = 'localhost';
	}

	if (!\preg_match("/" . $RefMatch . "/i", $ref))
	{
		$Return = "I'm sorry. I was unable to verify that you have submitted this request from a page on Equine-Ranch.com. Our security protocols do not allow me to accept data that could have originated elsewhere. Please be sure your computer is communicating with me properly (I hate when they keep secrets from me), and resubmit your request from an <a href=\"/index.php\">Equine-Ranch.com</a> page.";
	} else
	{
		$Return = \FALSE;
	}

	return $Return;

	# end ValidateReferrer()
}


function PrepFilters($Filters, $TableName)
{
	if (!is_array($Filters))
	{
		$ReturnValue = \FALSE;
		goto PrepFiltersReturn;
	}

	$Debug = \FALSE;

	# get a db connection
	$DB = new DatabaseFunctions();
	if (!$DB->connect())
	{
		$ReturnValue = \FALSE;
		goto PrepFiltersReturn;
	}

	# get a list of fields from the table
	$QueryArr = array();
	$QueryArr['Debug'] = $Debug;
	$QueryArr['Table'] = $TableName;

	$DB->ShowTables($QueryArr);
	if ($result = $DB->RunQuery())
	{
		$Columns = array();
		while ($row = $DB->FetchResults($result, 'assoc'))
		{
			foreach ($row as $ColumnName)
			{
				$Columns[] = $ColumnName['0'];
			}
		}
		$DB->CloseResult($result);
	} else
	{
		$ReturnValue = \FALSE;
		goto PrepFiltersReturn;
	}

	$ReturnValue['WhereClause'] = \NULL;
	$ReturnValue['BindParamsType'] = \NULL;
	$ReturnValue['BindParamsValue'] = array();

	# count the number of filters and set it in a variable for the loop
	$NumberOfFilters = count($Filters);
	for ($i = 0; $i < $NumberOfFilters; $i++)
	{
		# check that the field name sent is legit and not some sql injection attack
		if (!\in_array($Filters[$i]['field'], $Columns))
		{
			$ReturnValue = \FALSE;
			goto PrepFiltersReturn;
		}

		switch ($Filters[$i]['data']['type']) {
			case 'string':
				$ReturnValue['WhereClause'] .= " AND " . $Filters[$i]['field'] . " LIKE ?";
				$ReturnValue['BindParamsType'] .= 's';
				$ReturnValue['BindParamsValue'][] = '%' . $Filters[$i]['data']['value'] . '%';
				goto PrepFiltersReturn;
				break;
			case 'list':
				if (strstr($Filters[$i]['data']['value'], ','))
				{
					$ReturnValue['WhereClause'] .= " AND " . $Filters[$i]['field'] . " IN (";
					# explode the list sent by the UI
					$FiltersList = \explode(',', $Filters[$i]['data']['value']);
					# count the number of elements
					$NumberOfList = \count($FiltersList);
					# loop through them and build the where clause
					for ($j = 0; $j < $NumberOfList; $j++)
					{
						$ReturnValue['WhereClause'] .= "?,";
						$ReturnValue['BindParamsType'] = GetParamType($FiltersList[$j]);
						$ReturnValue['BindParamsValue'][] = $FiltersList[$j];
					}
					# trim the trailing comma
					$ReturnValue['WhereClause'] = \rtrim($ReturnValue['WhereClause'], ",");
					# finish the where clause
					$ReturnValue['WhereClause'] .= ")";
				} else
				{
					$ReturnValue['WhereClause'] .= " AND " . $Filters[$i]['field'] . " = ?";
					$ReturnValue['BindParamsType'] = GetParamType($Filters[$i]['data']['value']);
					$ReturnValue['BindParamsValue'][] = $Filters[$i]['data']['value'];
				}
				goto PrepFiltersReturn;
				break;
			case 'boolean':
				$ReturnValue['WhereClause'] .= " AND " . $Filters[$i]['field'] . " = ?";
				$ReturnValue['BindParamsType'] .= 's';
				$ReturnValue['BindParamsValue'][] = $Filters[$i]['data']['value'];
				goto PrepFiltersReturn;
				break;
			case 'numeric':
				switch ($Filters[$i]['data']['comparison']) {
					case 'ne':
						$ReturnValue['WhereClause'] .= " AND " . $Filters[$i]['field'] . " != ?";
						$ReturnValue['BindParamsType'] .= 's';
						$ReturnValue['BindParamsValue'][] = $Filters[$i]['data']['value'];
						break;
					case 'eq':
						$ReturnValue['WhereClause'] .= " AND " . $Filters[$i]['field'] . " = ?";
						$ReturnValue['BindParamsType'] .= 's';
						$ReturnValue['BindParamsValue'][] = $Filters[$i]['data']['value'];
						break;
					case 'lt':
						$ReturnValue['WhereClause'] .= " AND " . $Filters[$i]['field'] . " < ?";
						$ReturnValue['BindParamsType'] .= 's';
						$ReturnValue['BindParamsValue'][] = $Filters[$i]['data']['value'];
						break;
					case 'gt':
						$ReturnValue['WhereClause'] .= " AND " . $Filters[$i]['field'] . " > ?";
						$ReturnValue['BindParamsType'] .= 's';
						$ReturnValue['BindParamsValue'][] = $Filters[$i]['data']['value'];
						break;
				}
				goto PrepFiltersReturn;
				break;
			case 'date':
				switch ($Filters[$i]['data']['comparison']) {
					case 'ne':
						$qs .= " AND " . $Filters[$i]['field'] . " != '" . \date('Y-m-d', \strtotime($Filters[$i]['data']['value'])) . "'";
						break;
					case 'eq':
						$qs .= " AND " . $Filters[$i]['field'] . " = '" . \date('Y-m-d', \strtotime($Filters[$i]['data']['value'])) . "'";
						break;
					case 'lt':
						$qs .= " AND " . $Filters[$i]['field'] . " < '" . \date('Y-m-d', \strtotime($Filters[$i]['data']['value'])) . "'";
						break;
					case 'gt':
						$qs .= " AND " . $Filters[$i]['field'] . " > '" . \date('Y-m-d', \strtotime($Filters[$i]['data']['value'])) . "'";
						break;
				}
				break;
		}
	}

	PrepFiltersReturn: {
		return $ReturnValue;
	}

	# end PrepFilters()
}


function GetParamType($Value)
{
	# get the value's type
	$ValueType = \gettype($Value);

	# assign it a prepared statement type
	switch ($ValueType) {
		case 'integer':
			$ReturnValue = 'i';
			break;
		case 'double':
			$ReturnValue = 'd';
			break;
		case 'string':
			$ReturnValue = 's';
			break;
		default:
			$ReturnValue = \FALSE;
			break;
	}

	return $ReturnValue;

	# end GetParamType()
}


function ReplaceTemplatePlaceholders($Template, $Data, $TemplateIsFile = \FALSE)
{
	# example template variables {a} and {bc}
	# example $data array
	# $data = Array("a" => 'one', "bc" => 'two');
	if ($TemplateIsFile == \TRUE)
	{
		$Template = \file_get_contents($Template);
	}

	foreach ($Data as $Key => $Value)
	{
		$Return = \str_replace('{' . $Key . '}', $Value, $Template);
	}
	return $Return;

	# end ReplaceTemplatePlaceholders()
}


function SearchMultiArray_KeyStack($needle, $haystack)
{
	/**
	 * Gets the complete parent stack of a string array element if it is found
	 * within the parent array
	 *
	 * This will not search objects within an array, though I suspect you could
	 * tweak it easily enough to do that
	 *
	 * @param string $child The string array element to search for
	 * @param array $stack The stack to search within for the child
	 * @return array An array containing the parent stack for the child if found,
	 *               false otherwise
	 */
	$return = array();
	foreach ($haystack as $k => $v)
	{
		if (\is_array($v))
		{
			// If the current element of the array is an array, recurse it
			// and capture the return stack
			$haystack = SearchMultiArray_KeyStack($needle, $v);

			// If the return stack is an array, add it to the return
			if (\is_array($haystack) && !empty($haystack))
			{
				$return[$k] = $haystack;
			}
		} else
		{
			// Since we are not on an array, compare directly
			if ($v == $needle)
			{
				// And if we match, stack it and return it
				$return[$k] = $needle;
			}
		}
	}

	// Return the stack
	return empty($return)
		? false
		: $return;

	# end SearchMultiArray_KeyStack()
}


function SplitArray($AlleleArr, $Chunk, $Implode = \FALSE, $PreserveKeys = \FALSE)
{
	# split the alleles array into the individual traits
	$ByChunk = \array_chunk($AlleleArr, $Chunk, $PreserveKeys);
	if ($Implode == \TRUE ||
		$PreserveKeys == \TRUE)
	{
		foreach ($ByChunk as $Key => $Value)
		{
			$NewKey = ($PreserveKeys == \TRUE)
				? \rtrim(key($Value), "AB")
				: $Key;
			$ByChunk[$NewKey] = ($Implode == \TRUE)
				? \implode("", $ByChunk[$Key])
				: $ByChunk[$Key];
			unset($ByChunk[$Key]);
		}
	}
	return $ByChunk;

	# end SplitArray
}


function GetXMLTree($xmldata)
{
	# we want to know if an error occurs
	\ini_set('track_errors', '1');

	$xmlreaderror = false;

	$parser = \xml_parser_create('UTF-8');
	\xml_parser_set_option($parser, \XML_OPTION_SKIP_WHITE, 1);
	\xml_parser_set_option($parser, \XML_OPTION_CASE_FOLDING, 0);
	if (!\xml_parse_into_struct($parser, $xmldata, $vals, $index))
	{
		$xmlreaderror = true;
		echo "error";
	}
	\xml_parser_free($parser);

	if (!$xmlreaderror)
	{
		$result = array();
		$i = 0;
		if (isset($vals [$i]['attributes']))
		{
			foreach (\array_keys($vals [$i]['attributes']) as $attkey)
			{
				$attributes [$attkey] = $vals [$i]['attributes'][$attkey];
			}
		}

		$result [$vals [$i]['tag']] = \array_merge($attributes, GetChildren($vals, $i, 'open'));
	}

	\ini_set('track_errors', '0');
	return $result;

	# end GetXMLTree()
}


function GetChildren($vals, &$i, $type)
{
	if ($type == 'complete')
	{
		if (isset($vals [$i]['value']))
		{
			return ($vals [$i]['value']);
		} else
		{
			return '';
		}
	}

	$children = array(); # Contains node data
	# Loop through children
	while ($vals [++$i]['type'] != 'close')
	{
		$type = $vals [$i]['type'];
		# first check if we already have one and need to create an array
		if (isset($children [$vals [$i]['tag']]))
		{
			if (\is_array($children [$vals [$i]['tag']]))
			{
				$temp = \array_keys($children [$vals [$i]['tag']]);
				# there is one of these things already and it is itself an array
				if (\is_string($temp [0]))
				{
					$a = $children [$vals [$i]['tag']];
					unset($children [$vals [$i]['tag']]);
					$children [$vals [$i]['tag']][0] = $a;
				}
			} else
			{
				$a = $children [$vals [$i]['tag']];
				unset($children [$vals [$i]['tag']]);
				$children [$vals [$i]['tag']][0] = $a;
			}

			$children [$vals [$i]['tag']][] = GetChildren($vals, $i, $type);
		} else
		{
			$children [$vals [$i]['tag']] = GetChildren($vals, $i, $type);
		}

		# I don't think I need attributes but this is how I would do them:
		if (isset($vals [$i]['attributes']))
		{
			$attributes = array();
			foreach (array_keys($vals [$i]['attributes']) as $attkey)
			{
				$attributes [$attkey] = $vals [$i]['attributes'][$attkey];
			}
			# now check: do we already have an array or a value?
			if (isset($children [$vals [$i]['tag']]))
			{
				# case where there is an attribute but no value, a complete with an attribute in other words
				if ($children [$vals [$i]['tag']] == '')
				{
					unset($children [$vals [$i]['tag']]);
					$children [$vals [$i]['tag']] = $attributes;
				} elseif (is_array($children [$vals [$i]['tag']]))
				{
					$index = count($children [$vals [$i]['tag']]) - 1;
					// probably also have to check here whether the individual item is also an array or not or what... all a bit messy
					if ($children [$vals [$i]['tag']][$index] == '')
					{
						unset($children [$vals [$i]['tag']][$index]);
						$children [$vals [$i]['tag']][$index] = $attributes;
					}
					$children [$vals [$i]['tag']][$index] = \array_merge($children [$vals [$i]['tag']][$index], $attributes);
				} else
				{
					$value = $children [$vals [$i]['tag']];
					unset($children [$vals [$i]['tag']]);
					$children [$vals [$i]['tag']]['value'] = $value;
					$children [$vals [$i]['tag']] = \array_merge($children [$vals [$i]['tag']], $attributes);
				}
			} else
			{
				$children [$vals [$i]['tag']] = $attributes;
			}
		}
	}

	return $children;

	# end GetChildren()
}


function MailQuote($text, $marker)
{
	$text = \str_replace("\n", "\n$marker", \wordwrap($text, 70));
	return $text;

	# end MailQuote()
}


function Sanitize($Value, $DataType = \NULL)
{
	# this function needs a lot of work to be worth a shit,
	$ReturnValue = \NULL;
	if (\is_array($Value))
	{
		$ReturnValue = array();
		foreach ($Value as $key => $val)
		{
			$ReturnValue[$key] = Sanitize($val, $DataType);
		}
	} else
	{
		$DataType = (empty($DataType))
			? \gettype($Value)
			: $DataType;

		switch (\strtolower($DataType)) {
			case 'email':
				$Value = \filter_var($Value, \FILTER_SANITIZE_EMAIL);
				$ReturnValue = \filter_var($Value, \FILTER_VALIDATE_EMAIL);
				break;
			case 'date':
				if (date("Y-m-d", \strtotime($Value)) != $Value)
				{
					$ReturnValue = 'Invalid Date';
				} else
				{
					$ReturnValue = $Value;
				}
				break;
			case 'sort':
				if ($Value != 'ASC' &&
					$Value != 'DESC')
				{
					$ReturnValue = \FALSE;
				} else
				{
					$ReturnValue = $Value;
				}
				break;
			case 'html':
				$ReturnValue = \strip_tags(trim($Value), '<p><div><img><a><font><br><hr>');
				break;
			case 'name':
				if (!preg_match("/^[a-zA-Z\'\ ]+$/i", $Value))
				{
					$ReturnValue = \FALSE;
				} else
				{
					$ReturnValue = $Value;
				}
				break;
			case 'int':
				$Value = \filter_var($Value, \FILTER_SANITIZE_NUMBER_INT);
				$ReturnValue = \filter_var($Value, \FILTER_VALIDATE_INT);
				break;
			case 'float':
				$Value = \filter_var($Value, \FILTER_SANITIZE_NUMBER_FLOAT, \FILTER_FLAG_ALLOW_FRACTION | \FILTER_FLAG_ALLOW_THOUSAND);
				$ReturnValue = \filter_var($Value, \FILTER_VALIDATE_FLOAT, \FILTER_FLAG_ALLOW_THOUSAND);
				break;
			case 'boolean':
				//$ReturnValue = \filter_var($Value, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE);
				if (\is_bool($Value))
				{
					$ReturnValue = $Value;
				} else
				{
					$ReturnValue = \NULL;
				}
				break;
			case 'string':
			default:
				$Value = \strip_tags(trim($Value));
				$ReturnValue = \htmlspecialchars(\filter_var($Value, \FILTER_SANITIZE_STRING, \FILTER_FLAG_NO_ENCODE_QUOTES), \ENT_QUOTES, 'UTF-8');
				break;
		}
	}
	return $ReturnValue;

	# end Sanitize()
}


function ConvertCase($str, $case = 'upper')
{ //yours, courtesy of table4.com  :)
	switch ($case) {
		case "upper" :
		default:
			$str = \strtoupper($str);
			$pattern = '/&([A-Z])(UML|ACUTE|CIRC|TILDE|RING|';
			$pattern .= 'ELIG|GRAVE|SLASH|HORN|CEDIL|TH);/e';
			# convert the important bit back to lower
			$replace = "'&'.'\\1'.strtolower('\\2').';'";
			break;
		case "lower" :
			$str = \strtolower($str);
			break;
	}

	$str = \preg_replace($pattern, $replace, $str);
	return $str;

	# end ConvertCase()
}


function ordinalSuffix($number)
{
	$ends = array(
		'th',
		'st',
		'nd',
		'rd',
		'th',
		'th',
		'th',
		'th',
		'th',
		'th');

	if (($number % 100) >= 11 && ($number % 100) <= 13)
	{
		return $number . 'th';
	} else
	{
		return $number . $ends[$number % 10];
	}
}


function ChatHMACLink($nickName, $profileURL, $avatarURL, $link, $authKey)
{
	$cpID = \substr(\strrchr($link, ','), 1);
	$hmac = \md5($cpID . $nickName . $profileURL . $avatarURL . $authKey . \date('Ymd'));
	return $link . "&nn=" . $nickName . "&pu=" . $profileURL . "&au=" . $avatarURL . "&hmac=" . $hmac;

	# end ChatHMACLink()
}


function getAPI($command)
{
	$er = \error_reporting(0);
	$result = \file_get_contents("http://69.167.178.41:10055/?api." . $command);
	\error_reporting($er);
	return $result;

	# end getAPI()
}


function FirstLogin($ToID)
{
	# send them the welcome message

	$MsgBody = 'Welcome to Equine Ranch!

Before you get started, please be sure you have read all rules located at the following links.
http://www.equine-ranch.com/siterules.php
http://www.equine-ranch.com/rules.php
http://www.equine-ranch.com/tos.php
Your participation here is governed by these rules! They are strictly enforced and legally binding.

Now, you have officially joined our ranks as a fellow equine hobbyist!

Being a rancher on E-R (Equine Ranch) is very complex and can be a bit overwhelming to beginners. To help you out we have set up a knowledge base full of information! http://www.equine-ranch.com/voxknowledge/ It is organized by subject, and is searchable too! We also offer a mentorship program for new ranchers. If you are interested please email the Community Manager at whitney@snowwolfegames.com. Include ATTN: Mentor in the title and your player name and ranch id number in the message.

Please feel free to introduce yourself to other ranchers in the community in the New To The Neighborhood board in the E-R Commons.
http://www.equine-ranch.com/mybb/forumdisplay.php?fid=40

Some of the more experienced ranchers also have shared some of their secrets and tips to help out the greenhorns in the New Player Tips board. You can access The Commons through the main menu via The Courtyard > Interact > The Commons.
http://www.equine-ranch.com/mybb/forumdisplay.php?fid=15

Lastly, if you want to kick back and relax after a hard day&#039;s work on your ranch, you&#039;re welcome to hang out with your fellow ranchers on the Grapevine. You can access The Grapevine through the main menu via The Courtyard > Interact > The Grapevine. Please be sure to read the rules associated with using The Grapevine before you join.
http://www.equine-ranch.com/chat.php

Other helpful links for the beginning rancher:
Check out the breed population list, where you will find a list of all the available breeds in E-R, including the number of active horses currently in-game.
http://www.equine-ranch.com/breedspop.php

The crossbreeding guide is an important resource for the E-R breeder. It will help you determine if a cross between two different breeds will result in a registerable purebred or a grade.
http://www.equine-ranch.com/crossbreeding.php

The DNA Guide will help you get a grasp on horse coat genetics. You can find it in the main menu under Ranch Operations > Reference Guides > DNA Guide. There is also a very informative, and helpful genetics board on The Commons where you can discuss your horse&#039;s coat genetics, and get help from your fellow ranchers.
http://www.equine-ranch.com/colorchart.php
http://www.equine-ranch.com/mybb/forumdisplay.php?fid=22

The E-R Store is where you will be able to purchase in-game items such as expansions, breeding supplies, and custom import and picture credits. The Store is located under Your Account > Purchase Items > The E-R Store.
http://www.equine-ranch.com/store.php

We also have a Glossary that will help you with many horse and breeding related terms. You can find it in the main menu under Ranch Operations > Reference Guides > Glossary.
http://www.equine-ranch.com/voxknowledge/glossary.php


Again, welcome to Equine-Ranch! We hope you enjoy your time here.

- The E-R Staff ';

	$Subject = 'Welcome!';
	$FromID = 62915;

	try {
		# insert the mesage into the database
		$DataArr = array(
			"AdminID" => 9999999);
		$SendMsg = new Messaging($DataArr);
		$SendMsg->InsertInGameMessage($ToID, $Subject, $MsgBody, $FromID);
	} catch (\Exception $e) {
		\trigger_error($e, E_USER_ERROR);
	}

	# end FirstLogin()
}


function GeneratePassword($PWLength = 12, $alnumOnly = \FALSE)
{
	$Password = "";
	if ($alnumOnly == \FALSE)
	{
		$Seed = "2346789abcdefghjkmnpqrtuvwxyzABCDEFGHJKLMNPQRTUVWXYZ!@#$%^&*()-_=+[]\`";
	} else
	{
		$Seed = "2346789abcdefghjkmnpqrtuvwxyzABCDEFGHJKLMNPQRTUVWXYZ";
	}
	$MaxLength = \strlen($Seed);
	$i = 0;

	if ($PWLength > $MaxLength)
	{
		$PWLength = $MaxLength;
	}

	while ($i < $PWLength)
	{
		$char = \substr($Seed, \mt_rand(0, $MaxLength - 1), 1);
		if (!\strstr($Password, $char))
		{
			$Password .= $char;
			$i++;
		}
	}

	return $Password;

	# end ER_GeneratePassword()
}


function getPossessive($String)
{
	if ($rest = substr($String, -1) == 's')
	{
		$Possessive = $String . '&#039;';
	} else
	{
		$Possessive = $String . '&#039;s';
	}

	return $Possessive;

	# end getPossessive()
}


function getPronouns($Gender)
{
	switch ($Gender) {
		case 'Jill':
		case 'Sprite':
			$Pronouns = array(
				'SubjectPronoun'	 => 'she',
				'PossessivePronoun'	 => 'her',
				'ObjectPronoun'		 => 'her',
				'SelfPronoun'		 => 'herself');
			break;
		default:
			$Pronouns = array(
				'SubjectPronoun'	 => 'he',
				'PossessivePronoun'	 => 'his',
				'ObjectPronoun'		 => 'him',
				'SelfPronoun'		 => 'himself');
			break;
	}

	return $Pronouns;

	# end getPronouns()
}


function GetMonthName($MonthNumber)
{
	$Months = [
		"1"	 => "Aza",
		"2"	 => "Bercna",
		"3"	 => "Geuua",
		"4"	 => "Daaz",
		"5"	 => "Eyz",
		"6"	 => "Qerta",
		"7"	 => "Ezec",
		"8"	 => "Haal",
		"9"	 => "Thyth",
		"10" => "Iiz",
		"11" => "Chozma",
		"12" => "Laaz"
	];

	return $Months[$MonthNumber];

	# end getMonthName()
}


function GetMonthYearFromGM($Month)
{
	$Return = array();
	$Return['Year'] = \floor($Month / 12);
	$MonthNumber = 1 + (int) \fmod($Month, 12);
	$Return['MonthName'] = GetMonthName($MonthNumber);

	return $Return;

	# end GetMonthYearFromGM()
}


function getSalesTaxRate()
{
	return 0.07;

	# end getSalesTaxRate()
}


function getAllLocations()
{
	$LocationArray = [
		1	 => 'Dook Village',
		2	 => 'Blackfoot Mountain',
		3	 => 'Polecat City',
		4	 => 'Shivering Lake',
		5	 => 'Weasel Town'
	];

	return $LocationArray;

	# end getAllLocations()
}


function getALocation($id, $getName = TRUE)
{
	$Return = FALSE;

	$LocationArray = getAllLocations();

	if ($getName === FALSE)
	{
		$LocationArray = array_flip($LocationArray);
	}

	$Return = $LocationArray[$id];

	return $Return;

	# end getALocation()
}


function getEventDivisions()
{
	$Divisions = [
		'Novice',
		'Imported',
		'Homebred'
	];

	return $Divisions;

	# end getEventDivisions()
}


function getShopCapacity($Shop)
{
	switch ($Shop) {
		case 1:
			$ShopCapacity = 75;
			break;
		case 2:
			$ShopCapacity = 150;
			break;
		case 3:
			$ShopCapacity = 300;
			break;
		case 4:
			$ShopCapacity = 500;
			break;
		case 5:
			$ShopCapacity = 1000;
			break;
		default:
			$ShopCapacity = 75;
			break;
	}

	return $ShopCapacity;
}


function getShopUpgradeCost($NewShopSize)
{
	switch ($NewShopSize) {
		case 2:
			$Return['CostFD'] = 15000;
			$Return['CostFP'] = 10;
			$Return['TimeWait'] = 7;
			$Return['ItemsSold'] = 100;
			break;
		case 3:
			$Return['CostFD'] = 25000;
			$Return['CostFP'] = 20;
			$Return['TimeWait'] = 7;
			$Return['ItemsSold'] = 1000;
			break;
		case 4:
			$Return['CostFD'] = 100000;
			$Return['CostFP'] = 40;
			$Return['TimeWait'] = 7;
			$Return['ItemsSold'] = 5000;
			break;
		case 5:
			$Return['CostFD'] = 1000000;
			$Return['CostFP'] = 75;
			$Return['TimeWait'] = 7;
			$Return['ItemsSold'] = 10000;
			break;
		default:
			return FALSE;
			break;
	}

	return $Return;
}


function getLevelConstant()
{
	return 0.1;
}


function getPlayerLevel($XP)
{
	$Constant = getLevelConstant();

	return floor($Constant * sqrt($XP));
}


function getPlayerLevelRange($Level)
{
	$Constant = getLevelConstant();
	$NextLevel = $Level + 1;
	$LevelRange['min'] = pow($Level, 2) / $Constant;
	$LevelRange['max'] = pow($NextLevel, 2) / $Constant;

	return $LevelRange;
}


function getPurseShares($Placement)
{
	switch ($Placement) {
		case 1:
			$PurseShare = 0.3;
			break;
		case 2:
			$PurseShare = 0.25;
			break;
		case 3:
			$PurseShare = 0.2;
			break;
		case 4:
			$PurseShare = 0.15;
			break;
		case 5:
			$PurseShare = 0.1;
			break;
		default:
			$PurseShare = 0;
			break;
	}

	return $PurseShare;
}


function setShopSize($Shop)
{
	switch ($Shop) {
		case 1:
			$ShopSize = 'Converted Garage';
			break;
		case 2:
			$ShopSize = 'Flea Market Booth';
			break;
		case 3:
			$ShopSize = 'Corner Store';
			break;
		case 4:
			$ShopSize = 'Strip Mall';
			break;
		case 5:
			$ShopSize = 'Anchor Store';
			break;
		default:
			$ShopSize = '';
			break;
	}

	return $ShopSize;
}


function SetGameConstants(MySQL_DB $DB_Con)
{
	# get the game day and shutdown status
	$Columns = [
		[
			'Field' => 'Shutdown'
		],
		[
			'Field' => 'Reason'
		],
		[
			'Field' => 'Month'
		],
		[
			'Field' => 'MonthName'
		],
		[
			'Field' => 'Year'
		],
		[
			'Field' => 'Season'
		],
		[
			'Field' => 'Bank'
		],
		[
			'Field' => 'Shops'
		],
		[
			'Field' => 'PetStore'
		],
		[
			'Field' => 'Sales'
		],
		[
			'Field' => 'Leasing'
		],
		[
			'Field' => 'Semen'
		],
		[
			'Field' => 'Breeding'
		],
		[
			'Field' => 'Showing'
		],
		[
			'Field' => 'Messaging'
		],
		[
			'Field' => 'Health'
		],
		[
			'Field' => 'Training'
		],
		[
			'Field' => 'Animals'
		],
		[
			'Field' => 'Importing'
		],
		[
			'Field' => 'Account'
		],
		[
			'Field' => 'Hired'
		],
		[
			'Field' => 'Rehoming'
		],
		[
			'Field' => 'Classifieds'
		],
		[
			'Field' => 'Customs'
		],
		[
			'Field' => 'Store'
		],
		[
			'Field' => 'Notes'
		]
	];

	try {
		$DB_Con->SetTable('GameOps_GameStatus', 'go_gs')
			->SetColumns($Columns)
			->SetLimit(1);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		$GameStatus = $DB_Con->FetchResults($Result, "object");
		$DB_Con->CloseResult($Result);
	} catch (\InvalidArgumentException $e) {
		throw new \InvalidArgumentException($e->getMessage(), $e->getCode());
	} catch (\DBException $e) {
		throw new \DBException($e->getMessage(), $e->getCode());
	} catch (\Exception $e) {
		throw new \Exception($e->getMessage(), $e->getCode());
	} finally {
		$DB_Con->ResetQuery();
	}
	if (!$GameStatus)
	{
		throw new \NoResultException;
		exit();
	}

	# define constants that we use all over the site

	\define('Fez\AUTH_COOKIE', 'FezAuth');
	\define('Fez\FORMKEY', md5("badboysbadboyswhatchagonnado"));
	\define('Fez\SOMETHINGEXTRA', md5("somethingextra"));
	\define('Fez\DATE_TODAY', date("Y-m-d"));
	\define('Fez\TIME_NOW', date("H:i:s"));
	\define('Fez\DISPLAY_DATE', date("l, F jS, Y"));
	\define('Fez\DISPLAY_TIME', date("G:i"));

	\define('Fez\GAME_MONTH', $GameStatus->Month);
	\define('Fez\GAME_MONTH_NAME', $GameStatus->MonthName);
	\define('Fez\GAME_YEAR', $GameStatus->Year);
	\define('Fez\GAME_SEASON', $GameStatus->Season);
	\define('Fez\STATUS_SHUTDOWN', $GameStatus->Shutdown);
	\define('Fez\STATUS_REASON', $GameStatus->Reason);
	\define('Fez\STATUS_BANK', $GameStatus->Bank);
	\define('Fez\STATUS_SHOPS', $GameStatus->Shops);
	\define('Fez\STATUS_PETSTORE', $GameStatus->PetStore);
	\define('Fez\STATUS_SALES', $GameStatus->Sales);
	\define('Fez\STATUS_LEASING', $GameStatus->Leasing);
	\define('Fez\STATUS_SEMEN', $GameStatus->Semen);
	\define('Fez\STATUS_BREEDING', $GameStatus->Breeding);
	\define('Fez\STATUS_SHOWING', $GameStatus->Showing);
	\define('Fez\STATUS_MESSAGING', $GameStatus->Messaging);
	\define('Fez\STATUS_HEALTH', $GameStatus->Health);
	\define('Fez\STATUS_TRAINING', $GameStatus->Training);
	\define('Fez\STATUS_ANIMALS', $GameStatus->Animals);
	\define('Fez\STATUS_IMPORTING', $GameStatus->Importing);
	\define('Fez\STATUS_ACCOUNT', $GameStatus->Account);
	\define('Fez\STATUS_HIRED', $GameStatus->Hired);
	\define('Fez\STATUS_REHOMING', $GameStatus->Rehoming);
	\define('Fez\STATUS_CLASSIFIEDS', $GameStatus->Classifieds);
	\define('Fez\STATUS_CUSTOMS', $GameStatus->Customs);
	\define('Fez\STATUS_STORE', $GameStatus->Store);
	\define('Fez\STATUS_NOTES', $GameStatus->Notes);

	\define('Fez\ANIMAL_SESSION_LENGTH', 10);

	if (isset($_SERVER['HTTP_X_FORWARD_FOR']))
	{
		\define('Fez\IP', $_SERVER['HTTP_X_FORWARD_FOR']);
	} elseif (isset($_SERVER['REMOTE_ADDR']))
	{
		\define('Fez\IP', $_SERVER['REMOTE_ADDR']);
	}

	if (isset($_SERVER['HTTP_REFERER']))
	{
		\define('Fez\REFCHK', $_SERVER['HTTP_REFERER']);
	}

	# end SetGameConstants()
}


function prePrintR($param)
{
	echo '<pre>';
	print_r($param);
	echo '</pre>';

	# end prePrintR()
}


function hashPass($Password, $Cost = 12)
{
	$options = [
		'cost' => $Cost
	];
	return \password_hash($Password, \PASSWORD_BCRYPT, $options);

	# end hashPass()
}


function FezOrigPassHash($Password)
{
	$salt = "hhs5727592sjtu54d71gig";
	$string = "" . $salt . "" . ($Password) . "";
	$passie = md5($string);

	return $passie;

	# end FezOrigPassHash()
}


function InsertAlert(MySQL_DB $DB_Con, $ToID, $Msg, $MsgTitle)
{
	if (empty($Msg) ||
		empty($MsgTitle) ||
		empty($ToID))
	{
		return FALSE;
	}
	$Columns = [
		[
			'Field' => 'title'
		],
		[
			'Field' => 'description'
		],
		[
			'Field' => 'owner'
		]
	];
	$InsertVals = [
		[
			[
				'Value' => $MsgTitle
			],
			[
				'Value' => $Msg
			],
			[
				'Value' => $ToID
			]
		]
	];

	try {
		$DB_Con->SetTable('alerts')
			->SetColumns($Columns)
			->SetInsertValues($InsertVals);
		$Return = $DB_Con->Query('INSERT', 'Standard');
	} catch (\InvalidArgumentException $e) {
		throw $e;
	} catch (\NoResultException $e) {
		throw $e;
	} catch (\DBException $e) {
		throw $e;
	} catch (\Exception $e) {
		throw $e;
	} finally {
		$DB_Con->ResetQuery();
	}

	return $Return;

	# end InsertAlert()
}


function InsertActivity(MySQL_DB $DB_Con, $ToID, $Msg, $Type)
{
	if (empty($Msg) ||
		empty($Type) ||
		empty($ToID))
	{
		return FALSE;
	}

	$Columns = [
		[
			'Field' => 'owner'
		],
		[
			'Field' => 'type'
		],
		[
			'Field' => 'description'
		]
	];
	$InsertVals = [
		[
			[
				'Value' => $ToID
			],
			[
				'Value' => $Type
			],
			[
				'Value' => $Msg
			]
		]
	];

	try {
		$DB_Con->SetTable('activity')
			->SetColumns($Columns)
			->SetInsertValues($InsertVals);
		$Return = $DB_Con->Query('INSERT', 'Standard');
	} catch (\InvalidArgumentException $e) {
		throw $e;
	} catch (\NoResultException $e) {
		throw $e;
	} catch (\DBException $e) {
		throw $e;
	} catch (\Exception $e) {
		throw $e;
	} finally {
		$DB_Con->ResetQuery();
	}

	return $Return;

	# end InsertActivity()
}

