<?php

# createcompetitions.php
# by Nicole Ward
# <http://snowwolfegames.com>
#
# Copyright (c) 2009 - 2014 - SnowWolfe Games, LLC
# this script controls creating competitions

if (isset($_SERVER['HTTP_HOST']))
{
	exit('No joy.');
}

ini_set('display_errors', 'On');
require_once 'settings.php';
require_once Fez\INC_ROOT . 'configure.php';
$t1 = new Fez\code_timer;


# check to see if the game is closed for some reason. If so, exit gracefully.
if (Fez\STATUS_SHUTDOWN === 'down')
{
	exit("Exiting making competitions because game is closed.");
}


# check if the module is down for maintenance
if (Fez\STATUS_SHOWING === 'down')
{
	exit("Competitions are off.");
}

echo "Begin competition creation.\r\n";

# calculate the date
$RunDate = date("Y-m-d", strtotime("+7 days"));

# verify they were not already made

$Columns = [
	[
		'Field' => 'EventID'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'RunDate',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => $RunDate
	]
];

try {
	$DB_Con->SetTable('Events_CompetitionsList')
		->SetColumns($Columns)
		->SetWhere($Where)
		->SetLimit(1);
	$Result = $DB_Con->Query('SELECT', 'Standard');
	if ($DB_Con->NumRows($Result) > 0)
	{
		exit("The competitions were already made.");
	}
} catch (\InvalidArgumentException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
	echo $e->getMessage();
	exit("Exiting - Could not retrieve competitions status.");
} catch (\NoResultException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
	echo $e->getMessage();
	exit("Exiting - Could not retrieve competitions status.");
} catch (\DBException $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
	echo $e->getMessage();
	exit("Exiting - Could not retrieve competitions status.");
} catch (\Exception $e) {
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error);
	echo $e->getMessage();
	exit("Exiting - Could not retrieve competitions status.");
} finally {
	$DB_Con->CloseResult($Result)->ResetQuery();
}


# if none made make em
$Brackets = [
	'Local',
	'State',
	'National',
	'World'
];

$Disciplines = [
	'Conformation',
	'Racing',
	'Agility',
	'Hunting'
];

$Circuits = Fez\getAllLocations();

$Divisions = \Fez\getEventDivisions();

# prepare the insert query so we can utilize
# the prepared statements performance

$Columns = [
	[
		'Field' => 'EventName'
	],
	[
		'Field' => 'Discipline'
	],
	[
		'Field' => 'Bracket'
	],
	[
		'Field' => 'Division'
	],
	[
		'Field' => 'EntryFee'
	],
	[
		'Field' => 'Purse'
	],
	[
		'Field' => 'AddedPurse'
	],
	[
		'Field' => 'NumberEntries'
	],
	[
		'Field' => 'RunDate'
	],
	[
		'Field' => 'Circuit'
	]
];
$DB_Con->SetTable('Events_CompetitionsList')
	->SetColumns($Columns);
$Check = $DB_Con->Query('INSERT', 'Prepared');
if (!$Check)
{
	exit("Could not prepare insert statement");
}

foreach ($Disciplines as $Discipline)
{
	foreach ($Brackets as $Bracket)
	{
		foreach ($Circuits as $CircuitID => $Circuit)
		{
# set the number of events to make
# vary it by bracket
			switch ($Bracket) {
				case 'Local':
					$NumberToMake = 10;
					break;
				case 'State':
					$NumberToMake = 6;
					break;
				case 'National':
					$NumberToMake = 3;
					break;
				case 'World':
					$NumberToMake = 1;
					break;
				default:
					$NumberToMake = 5;
					break;
			}

			for ($i = 1; $i <= $NumberToMake; $i++)
			{
				switch ($Bracket) {
					case 'Local':
						$FeeArray = [
							5,
							5,
							5,
							5,
							10,
							10,
							10,
							10,
							15,
							15,
							15,
							20,
							20,
							20,
							25,
							25,
							25,
							30,
							30,
							50,
							50,
							75,
							75,
							100
						];
						$EntryFee = $FeeArray[mt_rand(0, count($FeeArray) - 1)];
						$BasePurse = $EntryFee * mt_rand(20, 150);
						break;
					case 'State':
						$FeeArray = [
							5,
							5,
							5,
							10,
							10,
							10,
							10,
							15,
							15,
							15,
							15,
							20,
							20,
							20,
							20,
							25,
							25,
							25,
							30,
							30,
							50,
							50,
							75,
							75,
							100,
							125
						];
						$EntryFee = $FeeArray[mt_rand(0, count($FeeArray) - 1)];
						$BasePurse = $EntryFee * mt_rand(25, 160);
						break;
					case 'National':
						$FeeArray = [
							10,
							10,
							15,
							15,
							15,
							20,
							20,
							20,
							20,
							25,
							25,
							25,
							25,
							30,
							30,
							30,
							50,
							50,
							75,
							75,
							100,
							125,
							150,
							200
						];
						$EntryFee = $FeeArray[mt_rand(0, count($FeeArray) - 1)];
						$BasePurse = $EntryFee * mt_rand(30, 170);
						break;
					case 'World':
						$FeeArray = [
							15,
							15,
							15,
							20,
							20,
							20,
							25,
							25,
							25,
							25,
							30,
							30,
							30,
							30,
							50,
							50,
							50,
							75,
							75,
							75,
							100,
							100,
							125,
							125,
							150,
							200,
							250,
							500
						];
						$EntryFee = $FeeArray[mt_rand(0, count($FeeArray) - 1)];
						$BasePurse = $EntryFee * mt_rand(4, 18);
						break;
				}


# chance of an added purse 1 in 5
				if (mt_rand(1, 5) == 1)
				{
					$AddedPurseArray = [
						10,
						10,
						25,
						25,
						50,
						50,
						50,
						75,
						75,
						75,
						100,
						100,
						100,
						100,
						150,
						150,
						150,
						250,
						250,
						250,
						400,
						400,
						500,
						500,
						750,
						750,
						1000,
						1250,
						1500
					];
					$AddedPurse = $AddedPurseArray[mt_rand(0, count($AddedPurseArray) - 1)];
					$TotalPurse = $BasePurse + $AddedPurse;
				} else
				{
					$AddedPurse = 0;
					$TotalPurse = $BasePurse;
				}


				$Division = $Divisions[mt_rand(0, count($Divisions) - 1)];

				$EventName = $Circuit . " " . $Bracket . " " . $Discipline;


# insert the information
				$Params = [
					$EventName,
					$Discipline,
					$Bracket,
					$Division,
					$EntryFee,
					$TotalPurse,
					$AddedPurse,
					$NumberEntries,
					$RunDate,
					$CircuitID
				];
				$DB_Con->SetInputParams($Params)
					->BindInputParams()
					->ExecutePreparedQuery();
			}
		}
	}
}
$DB_Con->CloseStmt()
	->ResetQuery();

echo 'Script completed in ' . $t1->stop_show_timer() . ' secs.';
