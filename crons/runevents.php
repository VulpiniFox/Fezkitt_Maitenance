<<<<<<< HEAD
<?php

# runevents.php
# by Nicole Ward
# <http://snowwolfegames.com>
#
# Copyright (c) 2009 - 2014 - SnowWolfe Games, LLC
# this script controls running events
# if the hour is 23 or 0 we have no events running
# so just exit and get on with life
if (date("H") == 22 ||
	date("H") == 23 ||
	date("H") == 0 ||
	date("H") == 1)
{
	exit("Exiting running events because it is 10 PM - 1 AM.");
}

ini_set('display_errors', 'On');
error_reporting(E_ALL);
echo 'running events';
require_once 'settings.php';
exit();
require_once Fez\INC_ROOT . 'configure.php';
require_once Fez\FUNC_ROOT . 'AnimalFuncs.php';

/*
  # check to see if the game is closed for some reason. If so, exit gracefully.
  if (Fez\STATUS_SHUTDOWN === 'down')
  {
  exit("Exiting running events because game is closed.");
  }
 */

# check if the module is down for maintenance
if (Fez\STATUS_SHOWING === 'down')
{
	exit("Events are off.");
}

$t1 = new Fez\code_timer;

# get the hour and the minute in variables
$Hour = date("G");
$Minute = date("i");

echo "starting runevents - \r\n";

$HoursArr = [
	2	 => 'Conformation',
	3	 => 'Racing',
	4	 => 'Agility',
	5	 => 'Hunting',
	6	 => 'Conformation',
	7	 => 'Racing',
	8	 => 'Agility',
	9	 => 'Hunting',
	10	 => 'Conformation',
	11	 => 'Racing',
	12	 => 'Agility',
	13	 => 'Hunting',
	14	 => 'Conformation',
	15	 => 'Racing',
	16	 => 'Agility',
	17	 => 'Hunting',
	18	 => 'Conformation',
	19	 => 'Racing',
	20	 => 'Agility',
	21	 => 'Hunting'
];
$MinutesArr = [
	'00' => 'Local',
	15	 => 'State',
	30	 => 'National',
	45	 => 'World'
];
$CircuitsArr = [
	2	 => '1',
	3	 => '1',
	4	 => '1',
	5	 => '1',
	6	 => '2',
	7	 => '2',
	8	 => '2',
	9	 => '2',
	10	 => '3',
	11	 => '3',
	12	 => '3',
	13	 => '3',
	14	 => '4',
	15	 => '4',
	16	 => '4',
	17	 => '4',
	18	 => '5',
	19	 => '5',
	20	 => '5',
	21	 => '5'
];
$Class = $HoursArr[$Hour];
$Bracket = $MinutesArr[$Minute];
$Circuit = $CircuitsArr[$Hour];

$Circuit = '1';
$Class = 'Conformation';
$Bracket = 'Local';


echo "Bracket: " . $Bracket . " Discipline: " . $Class . " Circuit: " . $Circuit . "\n";

# set variable for retrieving bracket defeated statistics later
$TrimmedBracket = rtrim($Bracket, " Champion");
$BracketDefeated = $TrimmedBracket . 'Defeated';
$BracketPoints = $TrimmedBracket . 'Points';

# see how many shows we have to run and figure
# how long script will sleep between them
$Columns = [
	[
		'Field' => 'EventID'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'Discipline',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => $Class
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'Bracket',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => $Bracket
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'Circuit',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => $Circuit
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'RunDate',
		'ExpressionOperator' => '<=',
		'SecondOperand'		 => Fez\DATE_TODAY
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'Completed',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	]
];

try {
	$DB_Con->SetTable('Events_CompetitionsList')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('SELECT', 'Standard');
	$EventCount = 0;
	while ($EventCheck = $DB_Con->FetchResults($Result, "assoc"))
	{
		\Fez\prePrintR($EventCheck);
		$Columns = [
			[
				'Field'			 => 'AnimalID',
				'SQLFunction'	 => 'COUNT',
				'ReturnAs'		 => 'totalferrets'
			]
		];
		$Where = [
			[
				'FirstOperand'		 => 'EventID',
				'ExpressionOperator' => '=',
				'SecondOperand'		 => $EventCheck['EventID']
			]
		];

		try {
			$DB_Con->SetTable('Events_Scores')
				->SetColumns($Columns)
				->SetWhere($Where);
			$Result2 = $DB_Con->Query('SELECT', 'Standard');
			$FerretCount = $DB_Con->FetchResults($Result2, "assoc");
			$DB_Con->CloseResult($Result2);
		} catch (\InvalidArgumentException $e) {
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
			exit();
		} catch (\NoResultException $e) {
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
			exit();
		} catch (\DBException $e) {
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
			exit();
		} catch (\Exception $e) {
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
			exit();
		} finally {
			$DB_Con->ResetQuery();
		}

                if ( empty($event) ) $event = '';
		if ($FerretCount['totalferrets'] == '0')
		{
			$sql = "
			DELETE FROM Events_CompetitionsList
			WHERE eventID = '" . $event->eventID . "'
			LIMIT 1";


			$Where = [
				[
					'FirstOperand'		 => 'EventID',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => $EventCheck['EventID']
				]
			];

			try {
				$DB_Con->SetTable('Events_CompetitionsList')
					->SetWhere($Where);
				$Result3 = $DB_Con->Query('DELETE', 'Standard');
				if ($Result3 === FALSE)
				{
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				}
			} catch (\InvalidArgumentException $e) {
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\NoResultException $e) {
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\DBException $e) {
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\Exception $e) {
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} finally {
				$DB_Con->ResetQuery();
			}

			continue;
		} else
		{
			$EventCount++;
		}
	}
	$DB_Con->CloseResult($Result);
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
	echo 'checkpoint 1';
	$DB_Con->ResetQuery()
		->Close();
	echo 'checkpoint 2';
}
echo 'ran query 1';

if ($EventCount > 1)
{
	$SleepFor = 460 / $EventCount;
} elseif ($EventCount == 1)
{
	$SleepFor = 0;
} else
{
	exit("Exiting running events. No events to run.");
}

echo $SleepFor . "\r\n";

# loop through the events one at a time and process them
for ($it = 0; $it < $EventCount; $it++)
{
	echo 'running event ' . $it;
	try {
		$DB_Con->connect();
	} catch (DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit("Exiting running events because "
			. "no DB connection was achieved.");
	} catch (Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit("Exiting running events because "
			. "no DB connection was achieved.");
	}

	$DB_Con->AutoCommit(FALSE);

	$Columns = [
		[
			'Field' => 'EventID'
		],
		[
			'Field' => 'EventName'
		],
		[
			'Field' => 'Bracket'
		],
		[
			'Field' => 'Division'
		],
		[
			'Field' => 'Purse'
		],
		[
			'Field' => 'PurseType'
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'Discipline',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $Class
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'Bracket',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $Bracket
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'Circuit',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $Circuit
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'RunDate',
			'ExpressionOperator' => '<=',
			'SecondOperand'		 => Fez\DATE_TODAY
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'Completed',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => 0
		]
	];

	try {
		$DB_Con->SetTable('Events_CompetitionsList')
			->SetColumns($Columns)
			->SetWhere($Where)
			->SetLimit(1);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		$EventToRun = $DB_Con->FetchResults($Result, "assoc");
		$DB_Con->CloseResult($Result);
	} catch (\InvalidArgumentException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}
	echo 'checkpoint 3';
	if (empty($EventToRun))
	{
		echo "failed to get the event record\n";
		$DB_Con->Close();
		sleep($SleepFor);
		continue;
	}
	Fez\prePrintR($EventToRun);
# score all entered animals
# select the entered animals
	$Columns = [
		[
			'Table'	 => 'e_s',
			'Field'	 => 'AnimalID'
		],
		[
			'Table'	 => 'e_s',
			'Field'	 => 'Score'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'owner'
		],
		[
			'Table'	 => 'p',
			'Field'	 => 'alias'
		]
	];
	$Where = [
		[
			'FirstOperandTable'	 => 'e_s',
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];
	$JoinTables = [
		[
			'JoinType'		 => 'LEFT',
			'JoinTable'		 => 'ferrets',
			'JoinAlias'		 => 'f',
			'JoinMethod'	 => 'ON',
			'JoinStatement'	 => '`e_s`.`AnimalID` = `f`.`id`'
		],
		[
			'JoinType'		 => 'LEFT',
			'JoinTable'		 => 'players',
			'JoinAlias'		 => 'p',
			'JoinMethod'	 => 'ON',
			'JoinStatement'	 => '`f`.`owner` = `p`.`id`'
		]
	];

	try {
		$DB_Con->SetTable('Events_Scores', 'e_s')
			->SetJoinTables($JoinTables)
			->SetColumns($Columns)
			->SetWhere($Where);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		if ($Result == FALSE)
		{
			exit("Failed to get the animal record to score.");
		} else
		{
			while ($EntryInfo = $DB_Con->FetchResults($Result, "assoc"))
			{
# if we have already scored this animal
# and it is running again because
# the script failed to complete,
# skip scoring this animal again

				if (!empty($EntryInfo['Score']))
				{
					continue;
				}


# default ClassSpecificScore to null
				$ClassSpecificScore = NULL;

# retrieve the info about the entered animals
				$Columns = [
					[
						'Field' => 'id'
					],
					[
						'Field' => 'age'
					],
					[
						'Field' => 'gender'
					],
					[
						'Field' => 'color'
					],
					[
						'Field' => 'temperament'
					],
					[
						'Field' => 'rest'
					],
					[
						'Field' => 'geneticstamina'
					],
					[
						'Field' => 'geneticstrength'
					],
					[
						'Field' => 'geneticspeed'
					],
					[
						'Field' => 'geneticintelligence'
					],
					[
						'Field' => 'geneticagility'
					],
					[
						'Field' => 'geneticcoat'
					],
					[
						'Field' => 'geneticconformation'
					],
					[
						'Field' => 'stamina'
					],
					[
						'Field' => 'strength'
					],
					[
						'Field' => 'speed'
					],
					[
						'Field' => 'intelligence'
					],
					[
						'Field' => 'agility'
					],
					[
						'Field' => 'coat'
					],
					[
						'Field' => 'health'
					],
					[
						'Field' => 'eyesight'
					],
					[
						'Field' => 'hearing'
					],
					[
						'Field' => 'vaccinated'
					],
					[
						'Field' => 'weight'
					],
					[
						'Field' => 'litter'
					],
					[
						'Field' => 'owner'
					]
				];
				$Where = [
					[
						'FirstOperand'		 => 'id',
						'ExpressionOperator' => '=',
						'SecondOperand'		 => $EntryInfo['AnimalID']
					]
				];

				try {
					$DB_Con->ResetQuery()
						->SetTable('ferrets')
						->SetColumns($Columns)
						->SetWhere($Where)
						->SetLimit(1);
					$Result2 = $DB_Con->Query('SELECT', 'Standard');
					$Animal = $DB_Con->FetchResults($Result2, "assoc");
					$DB_Con->CloseResult($Result2);
				} catch (\InvalidArgumentException $e) {
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\NoResultException $e) {
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\DBException $e) {
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\Exception $e) {
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} finally {
					$DB_Con->ResetQuery();
				}
				echo 'checkpoint 4';
# run it through the scoring function
				$ClassScore = \Fez\ScoreEvents_Class($Animal, $Class);
				echo 'checkpoint 5';
# save their score in the Events_Scores table
				$Columns = [
					[
						'Field'	 => 'Score',
						'Value'	 => $ClassScore
					],
					[
						'Field'	 => 'EventedBy',
						'Value'	 => $Animal['owner']
					],
					[
						'Field'			 => 'CompletedOn',
						'Value'			 => 'CURDATE()',
						'isSQLFunction'	 => 1
					]
				];
				$Where = [
					[
						'FirstOperand'		 => 'AnimalID',
						'ExpressionOperator' => '=',
						'SecondOperand'		 => $EntryInfo['AnimalID']
					],
					[
						'ClauseOperator'	 => 'AND',
						'FirstOperand'		 => 'EventID',
						'ExpressionOperator' => '=',
						'SecondOperand'		 => $EventToRun['EventID']
					],
				];

				try {
					$DB_Con->SetTable('Events_Scores')
						->SetColumns($Columns)
						->SetWhere($Where)
						->SetLimit(1);
					$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
					if ($UpdateResult === FALSE)
					{
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . 'No affected rows when updating score.';
						Fez\HandleError($error);
					}
				} catch (\InvalidArgumentException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\NoResultException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\DBException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\Exception $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} finally {
					$DB_Con->ResetQuery();
				}

				$DB_Con->CommitTrans();
			}
		}
		$DB_Con->CloseResult($Result);
	} catch (\InvalidArgumentException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}


# process the event
# steps:
# count total number of animals that competed
# sort the animals and assign placements and award winnings and points

	$Columns = [
		[
			'Field'			 => 'AnimalID',
			'SQLFunction'	 => 'COUNT',
			'ReturnAs'		 => 'totalferrets'
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];

	try {
		$DB_Con->SetTable('Events_Scores')
			->SetColumns($Columns)
			->SetWhere($Where);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		$FerretCount = $DB_Con->FetchResults($Result, "assoc");
		$DB_Con->CloseResult($Result);
	} catch (\InvalidArgumentException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}

# initialize placement variable to 0
	$Placement = 0;
	$PointReqs = \Fez\getPointReqs($Bracket);

	# columns and such for getting the animals by score
	$Columns = [
		[
			'Table'	 => 'e_s',
			'Field'	 => 'AnimalID'
		],
		[
			'Table'	 => 'e_s',
			'Field'	 => 'EventedBy'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'name'
		]
	];
	$Where = [
		[
			'FirstOperandTable'	 => 'e_s',
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];
	$JoinTables = [
		[
			'JoinType'		 => 'LEFT',
			'JoinTable'		 => 'ferrets',
			'JoinAlias'		 => 'f',
			'JoinMethod'	 => 'ON',
			'JoinStatement'	 => '`e_s`.`AnimalID` = `f`.`id`'
		]
	];
	$OrderBy = [
		[
			'Column'	 => 'Score',
			'Direction'	 => 'DESC'
		]
	];

	# columns and such for updating that animal's score record
	$PreparedColumns = [
		[
			'Field' => 'Placement'
		],
		[
			'Field' => 'PointsAwarded'
		],
		[
			'Field' => 'XPAwarded'
		]
	];
	$PreparedWhere = [
		[
			'FirstOperand'		 => 'AnimalID',
			'ExpressionOperator' => '='
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '='
		]
	];
	try {

		echo 'checkpoint 6';
		$DB_Con->SetTable('Events_Scores', 'e_s')
			->SetJoinTables($JoinTables)
			->SetColumns($Columns)
			->SetWhere($Where)
			->SetOrderBy($OrderBy);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		while ($ClassResults = $DB_Con->FetchResults($Result, "assoc"))
		{
			Fez\prePrintR($ClassResults);
# add one to the placement
			++$Placement;

# initialize our variables we will be using
			$Points = 0;
			$PlaceToIncrement = NULL;
			$Beaten = 0;
			$Winnings = 0;
			$XP = 0;

# if the animal was not disqualified and its score is not 0
# calculate the class winners
			switch ($Placement) {
				case 1:
					$PlaceToIncrement = 'firsts';
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));

# calculate the number of
# animals beaten in this class
					$Beaten = $FerretCount['totalferrets'] - $Placement;
					switch (TRUE) {
						case $Beaten >= $PointReqs['TwoPoint']:
							$Points = 2;
							break;
						case $Beaten >= $PointReqs['OnePoint']:
							$Points = 1;
							break;
						default:
							$Points = 0;
							break;
					}

					# calculate their XP earned
					$XP = $Points * $Beaten;
					break;
				case 2:
					$PlaceToIncrement = 'seconds';
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));
					break;
				case 3:
					$PlaceToIncrement = 'thirds';
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));
					break;
				case 4:
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));
					break;
				case 5:
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));
					break;
				default:
					break;
			}

# update the placement info in the scores table
# prepare the update query so we can best utilize the prepared statement

			$DB_Con->SetTable('Events_Scores')
				->SetColumns($PreparedColumns)
				->SetWhere($PreparedWhere)
				->SetLimit(1);
			$Check123 = $DB_Con->Query('UPDATE', 'Prepared');
			if ($Check123 == FALSE)
			{
# we can't continue with updating placements
				continue;
			}

			$Params = [
				$Placement,
				$Points,
				$XP,
				$ClassResults['AnimalID'],
				$EventToRun['EventID']
			];
			$DB_Con->SetInputParams($Params)
				->BindInputParams()
				->ExecutePreparedQuery();
			$DB_Con->CloseStmt();

# process payouts and win records
			if ($Winnings != 0)
			{
# pay the player

				$Columns = [
					[
						'Field'	 => $EventToRun['PurseType'],
						'Maths'	 => ' + ' . $Winnings
					]
				];
				$Where = [
					[
						'FirstOperand'		 => 'id',
						'ExpressionOperator' => '=',
						'SecondOperand'		 => $ClassResults['EventedBy']
					]
				];

				try {
					$DB_Con->SetTable('players')
						->SetColumns($Columns)
						->SetWhere($Where)
						->SetLimit(1);
					$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
				} catch (\InvalidArgumentException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\NoResultException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\DBException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\Exception $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} finally {
					//$DB_Con->ResetQuery();
				}

# set up the possessive of the animal's name
				$PossessiveName = \Fez\getPossessive($ClassResults['name']);

# record the transaction
				$Memo = '<a href="/ferret.php?id=' . $ClassResults['AnimalID'] . '>' . $PossessiveName . '</a> winnings from <a href="/event.php?id=' . $EventToRun['EventID'] . '</a> ' . $EventToRun['EventName'] . ' #' . $EventToRun['EventID'] . '</a> <span class="green">+$' . $Winnings;

				$Type = "Event Winnings!";
				try {
					Fez\InsertActivity($DB_Con, $ClassResults['EventedBy'], $Memo, $Type);
				} catch (Exception $e) {
					\ignore_user_abort(\TRUE);
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
				}
			}

# update win records for the animal and the player
			$Columns = [
				[
					'Field'	 => 'totalevents',
					'Maths'	 => '+ 1'
				],
				[
					'Field'	 => 'winnings',
					'Maths'	 => '+ ' . $Winnings
				]
			];
			if (!empty($PlaceToIncrement))
			{
				$Columns[] = [
					'Field'	 => $PlaceToIncrement,
					'Maths'	 => '+ 1'
				];
			}
			$Where = [
				[
					'FirstOperand'		 => 'id',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => $ClassResults['EventedBy']
				]
			];

			try {
				$DB_Con->SetTable('players')
					->SetColumns($Columns)
					->SetWhere($Where)
					->SetLimit(1);
				$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
			} catch (\InvalidArgumentException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\NoResultException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\DBException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\Exception $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} finally {
				//$DB_Con->ResetQuery();
			}

			$Columns = [
				[
					'Field'	 => 'points',
					'Maths'	 => '+ ' . $XP
				],
				[
					'Field'	 => 'bracketpoints',
					'Maths'	 => '+ ' . $Points
				],
				[
					'Field'	 => 'bracketdefeated',
					'Maths'	 => '+ ' . $Beaten
				],
				[
					'Field'	 => 'winnings',
					'Maths'	 => '+ ' . $Winnings
				],
				[
					'Field'	 => 'totalevents',
					'Maths'	 => '+ 1'
				]
			];
			if (!empty($PlaceToIncrement))
			{
				$Columns[] = [
					'Field'	 => $PlaceToIncrement,
					'Maths'	 => '+ 1'
				];
			}
			$Where = [
				[
					'FirstOperand'		 => 'id',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => $ClassResults['AnimalID']
				]
			];

			try {
				$DB_Con->SetTable('ferrets')
					->SetColumns($Columns)
					->SetWhere($Where)
					->SetLimit(1);
				$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
			} catch (\InvalidArgumentException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\NoResultException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\DBException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\Exception $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} finally {
				//$DB_Con->ResetQuery();
			}
		}
		$DB_Con->CloseResult($Result2);
	} catch (\InvalidArgumentException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}

# finish processing this specific event

	$Columns = [
		[
			'Table'	 => 'e_s',
			'Field'	 => 'AnimalID'
		],
		[
			'Table'	 => 'e_s',
			'Field'	 => 'XPAwarded'
		],
		[
			'Table'	 => 'e_s',
			'Field'	 => 'EventedBy'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'name'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'gender'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'bredby'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'points'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'bracket'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'bracketpoints'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'bracketdefeated'
		]
	];
	$JoinTables = [
		[
			'JoinType'		 => 'LEFT',
			'JoinTable'		 => 'ferrets',
			'JoinAlias'		 => 'f',
			'JoinMethod'	 => 'On',
			'JoinStatement'	 => '`e_s`.`AnimalID` = `f`.`id`'
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];
	echo 'checkpoint 7';
	try {
		$DB_Con->SetTable('Events_Scores', 'e_s')
			->SetJoinTables($JoinTables)
			->SetColumns($Columns)
			->SetWhere($Where);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		while ($row = $DB_Con->FetchResults($Result, 'assoc'))
		{
			Fez\prePrintR($row);
# update the any championships earned
			if ($row['title'] != 'World Champion')
			{
				$ALevel = Fez\getAnimalLevel($row['points']);
				$ALRange = Fez\getAnimalLevelRange($ALevel);
				if (($row['points'] - $row['XPAwarded']) > $ALRange['min'])
				{
					# this animal leveled up in this event
					# award the player some XP of their own
					$PlayerXP = $ALevel * 10;

					$Columns = [
						[
							'Field'	 => 'XP',
							'Maths'	 => '+ ' . $PlayerXP
						]
					];
					$Where = [
						[
							'FirstOperand'		 => 'id',
							'ExpressionOperator' => '=',
							'SecondOperand'		 => $row['EventedBy']
						]
					];

					try {
						$DB_Con->ResetQuery()
							->SetTable('players')
							->SetColumns($Columns)
							->SetWhere($Where)
							->SetLimit(1);
						$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
					} catch (\InvalidArgumentException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\NoResultException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\DBException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\Exception $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} finally {
						$DB_Con->ResetQuery();
					}
				}
				$TitleReqs = Fez\getTitleReqs($row['bracket']);

				if ($row['bracketpoints'] > $TitleReqs['Points'] &&
					$row['bracketdefeated'] > $TitleReqs['Defeated'])
				{
					$Title = $row['bracket'] . ' Champion';
					$NextBracket = Fez\getNextBracket($row['bracket']);

					$Columns = [
						[
							'Field'	 => 'bracket',
							'Value'	 => $NextBracket
						],
						[
							'Field'	 => 'bracketpoints',
							'Value'	 => 0
						],
						[
							'Field'	 => 'bracketdefeated',
							'Value'	 => 0
						],
						[
							'Field'	 => 'title',
							'Value'	 => $Title
						]
					];
					$Where = [
						[
							'FirstOperand'		 => 'id',
							'ExpressionOperator' => '=',
							'SecondOperand'		 => $row['AnimalID']
						]
					];

					try {
						$DB_Con->SetTable('ferrets')
							->SetColumns($Columns)
							->SetWhere($Where)
							->SetLimit(1);
						$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
					} catch (\InvalidArgumentException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\NoResultException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\DBException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\Exception $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} finally {
						$DB_Con->ResetQuery();
					}
				}
			}
		}
	} catch (\InvalidArgumentException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery()
			->CloseResult($Result);
	}

# mark the event completed
	$Columns = [
		[
			'Field'	 => 'Completed',
			'Value'	 => TRUE
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];

	try {
		$DB_Con->SetTable('Events_CompetitionsList')
			->SetColumns($Columns)
			->SetWhere($Where)
			->SetLimit(1);
		$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
	} catch (\InvalidArgumentException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}

	$DB_Con->CommitTrans();
	$DB_Con->AutoCommit(TRUE);

	$DB_Con->Close();
	sleep($SleepFor);
}
=======
<?php

# runevents.php
# by Nicole Ward
# <http://snowwolfegames.com>
#
# Copyright (c) 2009 - 2014 - SnowWolfe Games, LLC
# this script controls running events
# if the hour is 23 or 0 we have no events running
# so just exit and get on with life
if (date("H") == 22 ||
	date("H") == 23 ||
	date("H") == 0 ||
	date("H") == 1)
{
	exit("Exiting running events because it is 10 PM - 1 AM.");
}

ini_set('display_errors', 'On');
error_reporting(E_ALL);
echo 'running events';
require_once 'settings.php';
require_once Fez\INC_ROOT . 'configure.php';
require_once Fez\FUNC_ROOT . 'AnimalFuncs.php';

/*
  # check to see if the game is closed for some reason. If so, exit gracefully.
  if (Fez\STATUS_SHUTDOWN === 'down')
  {
  exit("Exiting running events because game is closed.");
  }
 */

# check if the module is down for maintenance
if (Fez\STATUS_SHOWING === 'down')
{
	exit("Events are off.");
}

$t1 = new Fez\code_timer;

# get the hour and the minute in variables
$Hour = date("G");
$Minute = date("i");

echo "starting runevents - \r\n";

$HoursArr = [
	2	 => 'Conformation',
	3	 => 'Racing',
	4	 => 'Agility',
	5	 => 'Hunting',
	6	 => 'Conformation',
	7	 => 'Racing',
	8	 => 'Agility',
	9	 => 'Hunting',
	10	 => 'Conformation',
	11	 => 'Racing',
	12	 => 'Agility',
	13	 => 'Hunting',
	14	 => 'Conformation',
	15	 => 'Racing',
	16	 => 'Agility',
	17	 => 'Hunting',
	18	 => 'Conformation',
	19	 => 'Racing',
	20	 => 'Agility',
	21	 => 'Hunting'
];
$MinutesArr = [
	'00' => 'Local',
	15	 => 'State',
	30	 => 'National',
	45	 => 'World'
];
$CircuitsArr = [
	2	 => '1',
	3	 => '1',
	4	 => '1',
	5	 => '1',
	6	 => '2',
	7	 => '2',
	8	 => '2',
	9	 => '2',
	10	 => '3',
	11	 => '3',
	12	 => '3',
	13	 => '3',
	14	 => '4',
	15	 => '4',
	16	 => '4',
	17	 => '4',
	18	 => '5',
	19	 => '5',
	20	 => '5',
	21	 => '5'
];
$Class = $HoursArr[$Hour];
$Bracket = $MinutesArr[$Minute];
$Circuit = $CircuitsArr[$Hour];

//$Circuit = '1';
//$Class = 'Conformation';
//$Bracket = 'Local';


echo "Bracket: " . $Bracket . " Discipline: " . $Class . " Circuit: " . $Circuit . "\n";

# set variable for retrieving bracket defeated statistics later
$TrimmedBracket = rtrim($Bracket, " Champion");
$BracketDefeated = $TrimmedBracket . 'Defeated';
$BracketPoints = $TrimmedBracket . 'Points';

# see how many shows we have to run and figure
# how long script will sleep between them
$Columns = [
	[
		'Field' => 'EventID'
	]
];
$Where = [
	[
		'FirstOperand'		 => 'Discipline',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => $Class
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'Bracket',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => $Bracket
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'Circuit',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => $Circuit
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'RunDate',
		'ExpressionOperator' => '<=',
		'SecondOperand'		 => Fez\DATE_TODAY
	],
	[
		'ClauseOperator'	 => 'AND',
		'FirstOperand'		 => 'Completed',
		'ExpressionOperator' => '=',
		'SecondOperand'		 => 0
	]
];

try {
	$DB_Con->SetTable('Events_CompetitionsList')
		->SetColumns($Columns)
		->SetWhere($Where);
	$Result = $DB_Con->Query('SELECT', 'Standard');
	$EventCount = 0;
	while ($EventCheck = $DB_Con->FetchResults($Result, "assoc"))
	{
		\Fez\prePrintR($EventCheck);
		$Columns = [
			[
				'Field'			 => 'AnimalID',
				'SQLFunction'	 => 'COUNT',
				'ReturnAs'		 => 'totalferrets'
			]
		];
		$Where = [
			[
				'FirstOperand'		 => 'EventID',
				'ExpressionOperator' => '=',
				'SecondOperand'		 => $EventCheck['EventID']
			]
		];

		try {
			$DB_Con->SetTable('Events_Scores')
				->SetColumns($Columns)
				->SetWhere($Where);
			$Result2 = $DB_Con->Query('SELECT', 'Standard');
			$FerretCount = $DB_Con->FetchResults($Result2, "assoc");
			$DB_Con->CloseResult($Result2);
		} catch (\InvalidArgumentException $e) {
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
			exit();
		} catch (\NoResultException $e) {
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
			exit();
		} catch (\DBException $e) {
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
			exit();
		} catch (\Exception $e) {
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
			exit();
		} finally {
			$DB_Con->ResetQuery();
		}

		if ($FerretCount['totalferrets'] == '0')
		{
			$sql = "
			DELETE FROM Events_CompetitionsList
			WHERE eventID = '" . $event->eventID . "'
			LIMIT 1";


			$Where = [
				[
					'FirstOperand'		 => 'EventID',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => $EventCheck['EventID']
				]
			];

			try {
				$DB_Con->SetTable('Events_CompetitionsList')
					->SetWhere($Where);
				$Result3 = $DB_Con->Query('DELETE', 'Standard');
				if ($Result3 === FALSE)
				{
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				}
			} catch (\InvalidArgumentException $e) {
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\NoResultException $e) {
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\DBException $e) {
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\Exception $e) {
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} finally {
				$DB_Con->ResetQuery();
			}

			continue;
		} else
		{
			$EventCount++;
		}
	}
	$DB_Con->CloseResult($Result);
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
	echo 'checkpoint 1';
	$DB_Con->ResetQuery()
		->Close();
	echo 'checkpoint 2';
}
echo 'ran query 1';

if ($EventCount > 1)
{
	$SleepFor = 460 / $EventCount;
} elseif ($EventCount == 1)
{
	$SleepFor = 0;
} else
{
	exit("Exiting running events. No events to run.");
}

echo $SleepFor . "\r\n";

# loop through the events one at a time and process them
for ($it = 0; $it < $EventCount; $it++)
{
	echo 'running event ' . $it;
	try {
		$DB_Con->connect();
	} catch (DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit("Exiting running events because "
			. "no DB connection was achieved.");
	} catch (Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit("Exiting running events because "
			. "no DB connection was achieved.");
	}

	$DB_Con->AutoCommit(FALSE);

	$Columns = [
		[
			'Field' => 'EventID'
		],
		[
			'Field' => 'EventName'
		],
		[
			'Field' => 'Bracket'
		],
		[
			'Field' => 'Division'
		],
		[
			'Field' => 'Purse'
		],
		[
			'Field' => 'PurseType'
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'Discipline',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $Class
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'Bracket',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $Bracket
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'Circuit',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $Circuit
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'RunDate',
			'ExpressionOperator' => '<=',
			'SecondOperand'		 => Fez\DATE_TODAY
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'Completed',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => 0
		]
	];

	try {
		$DB_Con->SetTable('Events_CompetitionsList')
			->SetColumns($Columns)
			->SetWhere($Where)
			->SetLimit(1);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		$EventToRun = $DB_Con->FetchResults($Result, "assoc");
		$DB_Con->CloseResult($Result);
	} catch (\InvalidArgumentException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}
	echo 'checkpoint 3';
	if (empty($EventToRun))
	{
		echo "failed to get the event record\n";
		$DB_Con->Close();
		sleep($SleepFor);
		continue;
	}
	Fez\prePrintR($EventToRun);
# score all entered animals
# select the entered animals
	$Columns = [
		[
			'Table'	 => 'e_s',
			'Field'	 => 'AnimalID'
		],
		[
			'Table'	 => 'e_s',
			'Field'	 => 'Score'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'owner'
		],
		[
			'Table'	 => 'p',
			'Field'	 => 'alias'
		]
	];
	$Where = [
		[
			'FirstOperandTable'	 => 'e_s',
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];
	$JoinTables = [
		[
			'JoinType'		 => 'LEFT',
			'JoinTable'		 => 'ferrets',
			'JoinAlias'		 => 'f',
			'JoinMethod'	 => 'ON',
			'JoinStatement'	 => '`e_s`.`AnimalID` = `f`.`id`'
		],
		[
			'JoinType'		 => 'LEFT',
			'JoinTable'		 => 'players',
			'JoinAlias'		 => 'p',
			'JoinMethod'	 => 'ON',
			'JoinStatement'	 => '`f`.`owner` = `p`.`id`'
		]
	];

	try {
		$DB_Con->SetTable('Events_Scores', 'e_s')
			->SetJoinTables($JoinTables)
			->SetColumns($Columns)
			->SetWhere($Where);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		if ($Result == FALSE)
		{
			exit("Failed to get the animal record to score.");
		} else
		{
			while ($EntryInfo = $DB_Con->FetchResults($Result, "assoc"))
			{
# if we have already scored this animal
# and it is running again because
# the script failed to complete,
# skip scoring this animal again

				if (!empty($EntryInfo['Score']))
				{
					continue;
				}


# default ClassSpecificScore to null
				$ClassSpecificScore = NULL;

# retrieve the info about the entered animals
				$Columns = [
					[
						'Field' => 'id'
					],
					[
						'Field' => 'age'
					],
					[
						'Field' => 'gender'
					],
					[
						'Field' => 'color'
					],
					[
						'Field' => 'temperament'
					],
					[
						'Field' => 'rest'
					],
					[
						'Field' => 'geneticstamina'
					],
					[
						'Field' => 'geneticstrength'
					],
					[
						'Field' => 'geneticspeed'
					],
					[
						'Field' => 'geneticintelligence'
					],
					[
						'Field' => 'geneticagility'
					],
					[
						'Field' => 'geneticcoat'
					],
					[
						'Field' => 'geneticconformation'
					],
					[
						'Field' => 'stamina'
					],
					[
						'Field' => 'strength'
					],
					[
						'Field' => 'speed'
					],
					[
						'Field' => 'intelligence'
					],
					[
						'Field' => 'agility'
					],
					[
						'Field' => 'coat'
					],
					[
						'Field' => 'health'
					],
					[
						'Field' => 'eyesight'
					],
					[
						'Field' => 'hearing'
					],
					[
						'Field' => 'vaccinated'
					],
					[
						'Field' => 'weight'
					],
					[
						'Field' => 'litter'
					],
					[
						'Field' => 'owner'
					]
				];
				$Where = [
					[
						'FirstOperand'		 => 'id',
						'ExpressionOperator' => '=',
						'SecondOperand'		 => $EntryInfo['AnimalID']
					]
				];

				try {
					$DB_Con->ResetQuery()
						->SetTable('ferrets')
						->SetColumns($Columns)
						->SetWhere($Where)
						->SetLimit(1);
					$Result2 = $DB_Con->Query('SELECT', 'Standard');
					$Animal = $DB_Con->FetchResults($Result2, "assoc");
					$DB_Con->CloseResult($Result2);
				} catch (\InvalidArgumentException $e) {
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\NoResultException $e) {
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\DBException $e) {
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\Exception $e) {
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} finally {
					$DB_Con->ResetQuery();
				}
				echo 'checkpoint 4';
# run it through the scoring function
				$ClassScore = \Fez\ScoreEvents_Class($Animal, $Class);
				echo 'checkpoint 5';
# save their score in the Events_Scores table
				$Columns = [
					[
						'Field'	 => 'Score',
						'Value'	 => $ClassScore
					],
					[
						'Field'	 => 'EventedBy',
						'Value'	 => $Animal['owner']
					],
					[
						'Field'			 => 'CompletedOn',
						'Value'			 => 'CURDATE()',
						'isSQLFunction'	 => 1
					]
				];
				$Where = [
					[
						'FirstOperand'		 => 'AnimalID',
						'ExpressionOperator' => '=',
						'SecondOperand'		 => $EntryInfo['AnimalID']
					],
					[
						'ClauseOperator'	 => 'AND',
						'FirstOperand'		 => 'EventID',
						'ExpressionOperator' => '=',
						'SecondOperand'		 => $EventToRun['EventID']
					],
				];

				try {
					$DB_Con->SetTable('Events_Scores')
						->SetColumns($Columns)
						->SetWhere($Where)
						->SetLimit(1);
					$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
					if ($UpdateResult === FALSE)
					{
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . 'No affected rows when updating score.';
						Fez\HandleError($error);
					}
				} catch (\InvalidArgumentException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\NoResultException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\DBException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\Exception $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} finally {
					$DB_Con->ResetQuery();
				}

				$DB_Con->CommitTrans();
			}
		}
		$DB_Con->CloseResult($Result);
	} catch (\InvalidArgumentException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}


# process the event
# steps:
# count total number of animals that competed
# sort the animals and assign placements and award winnings and points

	$Columns = [
		[
			'Field'			 => 'AnimalID',
			'SQLFunction'	 => 'COUNT',
			'ReturnAs'		 => 'totalferrets'
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];

	try {
		$DB_Con->SetTable('Events_Scores')
			->SetColumns($Columns)
			->SetWhere($Where);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		$FerretCount = $DB_Con->FetchResults($Result, "assoc");
		$DB_Con->CloseResult($Result);
	} catch (\InvalidArgumentException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}

# initialize placement variable to 0
	$Placement = 0;
	$PointReqs = \Fez\getPointReqs($Bracket);

	# columns and such for getting the animals by score
	$Columns = [
		[
			'Table'	 => 'e_s',
			'Field'	 => 'AnimalID'
		],
		[
			'Table'	 => 'e_s',
			'Field'	 => 'EventedBy'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'name'
		]
	];
	$Where = [
		[
			'FirstOperandTable'	 => 'e_s',
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];
	$JoinTables = [
		[
			'JoinType'		 => 'LEFT',
			'JoinTable'		 => 'ferrets',
			'JoinAlias'		 => 'f',
			'JoinMethod'	 => 'ON',
			'JoinStatement'	 => '`e_s`.`AnimalID` = `f`.`id`'
		]
	];
	$OrderBy = [
		[
			'Column'	 => 'Score',
			'Direction'	 => 'DESC'
		]
	];

	# columns and such for updating that animal's score record
	$PreparedColumns = [
		[
			'Field' => 'Placement'
		],
		[
			'Field' => 'PointsAwarded'
		],
		[
			'Field' => 'XPAwarded'
		]
	];
	$PreparedWhere = [
		[
			'FirstOperand'		 => 'AnimalID',
			'ExpressionOperator' => '='
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '='
		]
	];
	try {

		echo 'checkpoint 6';
		$DB_Con->SetTable('Events_Scores', 'e_s')
			->SetJoinTables($JoinTables)
			->SetColumns($Columns)
			->SetWhere($Where)
			->SetOrderBy($OrderBy);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		while ($ClassResults = $DB_Con->FetchResults($Result, "assoc"))
		{
			Fez\prePrintR($ClassResults);
# add one to the placement
			++$Placement;

# initialize our variables we will be using
			$Points = 0;
			$PlaceToIncrement = NULL;
			$Beaten = 0;
			$Winnings = 0;
			$XP = 0;

# if the animal was not disqualified and its score is not 0
# calculate the class winners
			switch ($Placement) {
				case 1:
					$PlaceToIncrement = 'firsts';
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));

# calculate the number of
# animals beaten in this class
					$Beaten = $FerretCount['totalferrets'] - $Placement;
					switch (TRUE) {
						case $Beaten >= $PointReqs['TwoPoint']:
							$Points = 2;
							break;
						case $Beaten >= $PointReqs['OnePoint']:
							$Points = 1;
							break;
						default:
							$Points = 0;
							break;
					}

					# calculate their XP earned
					$XP = $Points * $Beaten;
					break;
				case 2:
					$PlaceToIncrement = 'seconds';
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));
					break;
				case 3:
					$PlaceToIncrement = 'thirds';
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));
					break;
				case 4:
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));
					break;
				case 5:
					$Winnings = round($EventToRun['Purse'] * \Fez\getPurseShares($Placement));
					break;
				default:
					break;
			}

# update the placement info in the scores table
# prepare the update query so we can best utilize the prepared statement

			$DB_Con->SetTable('Events_Scores')
				->SetColumns($PreparedColumns)
				->SetWhere($PreparedWhere)
				->SetLimit(1);
			$Check123 = $DB_Con->Query('UPDATE', 'Prepared');
			if ($Check123 == FALSE)
			{
# we can't continue with updating placements
				continue;
			}

			$Params = [
				$Placement,
				$Points,
				$XP,
				$ClassResults['AnimalID'],
				$EventToRun['EventID']
			];
			$DB_Con->SetInputParams($Params)
				->BindInputParams()
				->ExecutePreparedQuery();
			$DB_Con->CloseStmt();

# process payouts and win records
			if ($Winnings != 0)
			{
# pay the player

				$Columns = [
					[
						'Field'	 => $EventToRun['PurseType'],
						'Maths'	 => ' + ' . $Winnings
					]
				];
				$Where = [
					[
						'FirstOperand'		 => 'id',
						'ExpressionOperator' => '=',
						'SecondOperand'		 => $ClassResults['EventedBy']
					]
				];

				try {
					$DB_Con->SetTable('players')
						->SetColumns($Columns)
						->SetWhere($Where)
						->SetLimit(1);
					$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
				} catch (\InvalidArgumentException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\NoResultException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\DBException $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} catch (\Exception $e) {
					$DB_Con->RollbackTrans();
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
					exit();
				} finally {
					//$DB_Con->ResetQuery();
				}

# set up the possessive of the animal's name
				$PossessiveName = \Fez\getPossessive($ClassResults['name']);

# record the transaction
				$Memo = '<a href="/ferret.php?id=' . $ClassResults['AnimalID'] . '>' . $PossessiveName . '</a> winnings from <a href="/event.php?id=' . $EventToRun['EventID'] . '</a> ' . $EventToRun['EventName'] . ' #' . $EventToRun['EventID'] . '</a> <span class="green">+$' . $Winnings;

				$Type = "Event Winnings!";
				try {
					Fez\InsertActivity($DB_Con, $ClassResults['EventedBy'], $Memo, $Type);
				} catch (Exception $e) {
					\ignore_user_abort(\TRUE);
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
				}
			}

# update win records for the animal and the player
			$Columns = [
				[
					'Field'	 => 'totalevents',
					'Maths'	 => '+ 1'
				],
				[
					'Field'	 => 'winnings',
					'Maths'	 => '+ ' . $Winnings
				]
			];
			if (!empty($PlaceToIncrement))
			{
				$Columns[] = [
					'Field'	 => $PlaceToIncrement,
					'Maths'	 => '+ 1'
				];
			}
			$Where = [
				[
					'FirstOperand'		 => 'id',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => $ClassResults['EventedBy']
				]
			];

			try {
				$DB_Con->SetTable('players')
					->SetColumns($Columns)
					->SetWhere($Where)
					->SetLimit(1);
				$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
			} catch (\InvalidArgumentException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\NoResultException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\DBException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\Exception $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} finally {
				//$DB_Con->ResetQuery();
			}

			$Columns = [
				[
					'Field'	 => 'points',
					'Maths'	 => '+ ' . $XP
				],
				[
					'Field'	 => 'bracketpoints',
					'Maths'	 => '+ ' . $Points
				],
				[
					'Field'	 => 'bracketdefeated',
					'Maths'	 => '+ ' . $Beaten
				],
				[
					'Field'	 => 'winnings',
					'Maths'	 => '+ ' . $Winnings
				],
				[
					'Field'	 => 'totalevents',
					'Maths'	 => '+ 1'
				]
			];
			if (!empty($PlaceToIncrement))
			{
				$Columns[] = [
					'Field'	 => $PlaceToIncrement,
					'Maths'	 => '+ 1'
				];
			}
			$Where = [
				[
					'FirstOperand'		 => 'id',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => $ClassResults['AnimalID']
				]
			];

			try {
				$DB_Con->SetTable('ferrets')
					->SetColumns($Columns)
					->SetWhere($Where)
					->SetLimit(1);
				$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
			} catch (\InvalidArgumentException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\NoResultException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\DBException $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} catch (\Exception $e) {
				$DB_Con->RollbackTrans();
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
				exit();
			} finally {
				//$DB_Con->ResetQuery();
			}
		}
		$DB_Con->CloseResult($Result2);
	} catch (\InvalidArgumentException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}

# finish processing this specific event

	$Columns = [
		[
			'Table'	 => 'e_s',
			'Field'	 => 'AnimalID'
		],
		[
			'Table'	 => 'e_s',
			'Field'	 => 'XPAwarded'
		],
		[
			'Table'	 => 'e_s',
			'Field'	 => 'EventedBy'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'name'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'gender'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'bredby'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'points'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'bracket'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'bracketpoints'
		],
		[
			'Table'	 => 'f',
			'Field'	 => 'bracketdefeated'
		]
	];
	$JoinTables = [
		[
			'JoinType'		 => 'LEFT',
			'JoinTable'		 => 'ferrets',
			'JoinAlias'		 => 'f',
			'JoinMethod'	 => 'On',
			'JoinStatement'	 => '`e_s`.`AnimalID` = `f`.`id`'
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];
	echo 'checkpoint 7';
	try {
		$DB_Con->SetTable('Events_Scores', 'e_s')
			->SetJoinTables($JoinTables)
			->SetColumns($Columns)
			->SetWhere($Where);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		while ($row = $DB_Con->FetchResults($Result, 'assoc'))
		{
			Fez\prePrintR($row);
# update the any championships earned
			if ($row['title'] != 'World Champion')
			{
				$ALevel = Fez\getAnimalLevel($row['points']);
				$ALRange = Fez\getAnimalLevelRange($ALevel);
				if (($row['points'] - $row['XPAwarded']) > $ALRange['min'])
				{
					# this animal leveled up in this event
					# award the player some XP of their own
					$PlayerXP = $ALevel * 10;

					$Columns = [
						[
							'Field'	 => 'XP',
							'Maths'	 => '+ ' . $PlayerXP
						]
					];
					$Where = [
						[
							'FirstOperand'		 => 'id',
							'ExpressionOperator' => '=',
							'SecondOperand'		 => $row['EventedBy']
						]
					];

					try {
						$DB_Con->ResetQuery()
							->SetTable('players')
							->SetColumns($Columns)
							->SetWhere($Where)
							->SetLimit(1);
						$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
					} catch (\InvalidArgumentException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\NoResultException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\DBException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\Exception $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} finally {
						$DB_Con->ResetQuery();
					}
				}
				$TitleReqs = Fez\getTitleReqs($row['bracket']);

				if ($row['bracketpoints'] > $TitleReqs['Points'] &&
					$row['bracketdefeated'] > $TitleReqs['Defeated'])
				{
					$Title = $row['bracket'] . ' Champion';
					$NextBracket = Fez\getNextBracket($row['bracket']);

					$Columns = [
						[
							'Field'	 => 'bracket',
							'Value'	 => $NextBracket
						],
						[
							'Field'	 => 'bracketpoints',
							'Value'	 => 0
						],
						[
							'Field'	 => 'bracketdefeated',
							'Value'	 => 0
						],
						[
							'Field'	 => 'title',
							'Value'	 => $Title
						]
					];
					$Where = [
						[
							'FirstOperand'		 => 'id',
							'ExpressionOperator' => '=',
							'SecondOperand'		 => $row['AnimalID']
						]
					];

					try {
						$DB_Con->SetTable('ferrets')
							->SetColumns($Columns)
							->SetWhere($Where)
							->SetLimit(1);
						$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
					} catch (\InvalidArgumentException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\NoResultException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\DBException $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} catch (\Exception $e) {
						$DB_Con->RollbackTrans();
						$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
						Fez\HandleError($error);
						exit();
					} finally {
						$DB_Con->ResetQuery();
					}
				}
			}
		}
	} catch (\InvalidArgumentException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery()
			->CloseResult($Result);
	}

# mark the event completed
	$Columns = [
		[
			'Field'	 => 'Completed',
			'Value'	 => TRUE
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'EventID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $EventToRun['EventID']
		]
	];

	try {
		$DB_Con->SetTable('Events_CompetitionsList')
			->SetColumns($Columns)
			->SetWhere($Where)
			->SetLimit(1);
		$UpdateResult = $DB_Con->Query('UPDATE', 'Standard');
	} catch (\InvalidArgumentException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\NoResultException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\DBException $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} catch (\Exception $e) {
		$DB_Con->RollbackTrans();
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error);
		exit();
	} finally {
		$DB_Con->ResetQuery();
	}

	$DB_Con->CommitTrans();
	$DB_Con->AutoCommit(TRUE);

	$DB_Con->Close();
	sleep($SleepFor);
}
>>>>>>> origin/master
