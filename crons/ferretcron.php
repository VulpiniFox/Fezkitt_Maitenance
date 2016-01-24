<?php

if (isset($_SERVER['HTTP_HOST']))
{
	exit('No joy.');
}
ini_set('display_errors', 'On');
require_once 'settings.php';
require_once Fez\INC_ROOT . 'configure.php';
$t1 = new Fez\code_timer;

require_once Fez\FUNC_ROOT . 'AnimalFuncs.php';

$dbhost = '10.0.0.6:3306';
$dbuser = 'fez_user';
$dbpass = 'T8rmQ6c!P$byGWA';
$dbname = 'fezgamedb';


$link = @mysql_pconnect($dbhost, $dbuser, $dbpass) or die("A link couldn't be made to the MySQL database server. Please try again. If the problem persists, please contact the server administrator.");
$selectdb = @mysql_select_db($dbname, $link) or die("The MySQL database couldn't be selected. Please try again. If the problem persists, please contact the server administrator.");

# age abandoned ferrets
$ageshelterferrets = mysql_query("UPDATE ferrets SET age = age + 1 WHERE owner = 0");

$getallunlockedaccounts = mysql_query("SELECT * FROM players WHERE accountlock = 0 ORDER BY id ASC");
while ($accl = mysql_fetch_array($getallunlockedaccounts))
{
// LITTER STUFF GOES BELOW!

	$updatelit = mysql_query("UPDATE ferrets SET litter = litter - 1 WHERE litter > '0' and owner = " . $accl['id']);
	$getbby = mysql_query("SELECT id, age FROM kits WHERE age < 6 AND owner = " . $accl['id']);
	while ($Ferret = mysql_fetch_assoc($getbby))
	{
		switch (++$Ferret['age']) {
			case 1:
				$weight = \round(\mt_rand(140, 180) / 10, 1);
				break;
			case 2:
				$weight = \round(\mt_rand(340, 580) / 10, 1);
				break;
			case 3:
				$weight = \round(\mt_rand(460, 650) / 10, 1);
				break;
			case 4:
				$weight = \round(\mt_rand(640, 690) / 10, 1);
				break;
			case 5:
				$weight = \round(\mt_rand(760, 1260) / 10, 1);
				break;
			default:
				$weight = 0;
				break;
		}
		$sql = "UPDATE kits SET age = age + 1, currentweight = currentweight + " . $weight . " WHERE id = " . $Ferret['id'];
		$updatekit = mysql_query($sql);
	}

// WHILE LOOP KITS INTO FERRETS TABLE WHERE AGE IS 6

	$getbby = mysql_query("SELECT * FROM kits WHERE age >= 6 AND keep = 1 AND owner = " . $accl['id']);
	while ($Ferret = mysql_fetch_assoc($getbby))
	{

// GET MUMMY

		$mommy = mysql_query("SELECT immunity, vaccinated, fleas FROM ferrets WHERE id = " . $Ferret['mother'] . " LIMIT 1");
		$mom = mysql_fetch_assoc($mommy);


// GET DADDY

		$daddy = mysql_query("SELECT immunity FROM ferrets WHERE id = " . $Ferret['father'] . " LIMIT 1");
		$dad = mysql_fetch_assoc($daddy);


		$immunity1 = ($mom['immunity'] + $dad['immunity']) / 2.5;
		$immunity = ceil($immunity1);


		$desc = mysql_real_escape_String("No description!");


		if ($mom['vaccinated'] != '0')
		{
			$vac = "5";
		} else
		{
			$vac = "0";
		}

		$bbyflea = $mom['fleas'] / 2;


		$DB_Con->AutoCommit(FALSE);
		$DB_Con->StartTrans();

		$Animal = [
			'age'				 => $Ferret['age'],
			'gender'			 => $Ferret['gender'],
			'color'				 => $Ferret['color'],
			'temperament'		 => $Ferret['temperament'],
			'weight'			 => $Ferret['weight'],
			'owner'				 => $Ferret['owner'],
			'breeder'			 => $Ferret['owner'],
			'hunger'			 => '100',
			'thirst'			 => '100',
			'boredom'			 => '100',
			'cagehygiene'		 => '100',
			'appearance'		 => '100',
			'immunity'			 => $immunity,
			'rest'				 => 100,
			'GenStamina'		 => $Ferret['geneticstamina'],
			'GenStrength'		 => $Ferret['geneticstrength'],
			'GenSpeed'			 => $Ferret['geneticspeed'],
			'GenIntelligence'	 => $Ferret['geneticintelligence'],
			'GenAgility'		 => $Ferret['geneticagility'],
			'GenCoat'			 => $Ferret['geneticcoat'],
			'GenConformation'	 => $Ferret['geneticconformation'],
			'Stamina'			 => $Ferret['stamina'],
			'Strength'			 => $Ferret['strength'],
			'Speed'				 => $Ferret['speed'],
			'Intelligence'		 => $Ferret['intelligence'],
			'Agility'			 => $Ferret['agility'],
			'Coat'				 => $Ferret['coat'],
			'health'			 => $randhealth,
			'eyesight'			 => $Ferret['eyesight'],
			'hearing'			 => $Ferret['hearing'],
			'desc'				 => $desc,
			'mother'			 => $Ferret['mother'],
			'father'			 => $Ferret['father'],
			'image'				 => $Ferret['image']
		];

		try {
			Fez\InsertAnimal($Animal, $DB_Con);
		} catch (\InvalidArgumentException $e) {
			$DB_Con->RollbackTrans();
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
		} catch (\NoResultException $e) {
			$DB_Con->RollbackTrans();
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
		} catch (\DBException $e) {
			$DB_Con->RollbackTrans();
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
		} catch (\Exception $e) {
			$DB_Con->RollbackTrans();
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
		} finally {
			$DB_Con->ResetQuery();
		}

		$AnimalID = $DB_Con->InsertID();
		if (empty($AnimalID))
		{
			$DB_Con->RollbackTrans();
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . "Unable to get insert id.";
			Fez\HandleError($error);
		}

		$Ferret['StaminaGenes'] = unserialize($Ferret['StaminaGenes']);
		$Ferret['StrengthGenes'] = unserialize($Ferret['StrengthGenes']);
		$Ferret['SpeedGenes'] = unserialize($Ferret['SpeedGenes']);
		$Ferret['IntelligenceGenes'] = unserialize($Ferret['IntelligenceGenes']);
		$Ferret['AgilityGenes'] = unserialize($Ferret['AgilityGenes']);
		$Ferret['CoatGenes'] = unserialize($Ferret['CoatGenes']);
		$Ferret['ConformationGenes'] = unserialize($Ferret['ConformationGenes']);

		foreach ($Ferret['StaminaGenes'] as $Key => $Value)
		{
			$Genes[$Key] = $Value;
		}

		foreach ($Ferret['StrengthGenes'] as $Key => $Value)
		{
			$Genes[$Key] = $Value;
		}

		foreach ($Ferret['SpeedGenes'] as $Key => $Value)
		{
			$Genes[$Key] = $Value;
		}

		foreach ($Ferret['IntelligenceGenes'] as $Key => $Value)
		{
			$Genes[$Key] = $Value;
		}

		foreach ($Ferret['AgilityGenes'] as $Key => $Value)
		{
			$Genes[$Key] = $Value;
		}

		foreach ($Ferret['CoatGenes'] as $Key => $Value)
		{
			$Genes[$Key] = $Value;
		}

		foreach ($Ferret['ConformationGenes'] as $Key => $Value)
		{
			$Genes[$Key] = $Value;
		}

		$Genes['Agouti'] = [
			1	 => $Ferret['Agouti1'],
			2	 => $Ferret['Agouti2']
		];
		$Genes['Albino'] = [
			1	 => $Ferret['Albino1'],
			2	 => $Ferret['Albino2']
		];
		$Genes['Grey'] = [
			1	 => $Ferret['Grey1'],
			2	 => $Ferret['Grey2']
		];
		$Genes['Hair'] = [
			1	 => $Ferret['Hair1'],
			2	 => $Ferret['Hair2']
		];
		$Genes['Mitt'] = [
			1	 => $Ferret['Mitt1'],
			2	 => $Ferret['Mitt2']
		];
		$Genes['Pointed'] = [
			1	 => $Ferret['Pointed1'],
			2	 => $Ferret['Pointed2']
		];
		$Genes['Waard'] = [
			1	 => $Ferret['Waard1'],
			2	 => $Ferret['Waard2']
		];
		$Genes['White'] = [
			1	 => $Ferret['White1'],
			2	 => $Ferret['White2']
		];

		try {
			Fez\InsertGenes($AnimalID, $Genes, $DB_Con);
		} catch (\InvalidArgumentException $e) {
			$DB_Con->RollbackTrans();
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
		} catch (\NoResultException $e) {
			$DB_Con->RollbackTrans();
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
		} catch (\DBException $e) {
			$DB_Con->RollbackTrans();
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
		} catch (\Exception $e) {
			$DB_Con->RollbackTrans();
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error);
		} finally {
			$DB_Con->ResetQuery();
		}

		$DB_Con->CommitTrans();
		$DB_Con->AutoCommit(TRUE);

		# delete just this kit
		$deleteoldkit = mysql_query("DELETE FROM kits WHERE id = " . $Ferret['id'] . " LIMIT 1");

// ALERT ALL MEMBERS OF NEW FERRET!!

		$MsgTitle = "New Kit!";
		$Msg = "You deicided to keep " . $bby['name'] . " from a litter, you will find them in your Ferretry!";

		Fez\InsertAlert($DB_Con, $bby['owner'], $Msg, $MsgTitle);
	}

// ALERT ALL MEMBERS OF NEW LITTER!!

	$getmat = mysql_query("SELECT owner FROM kits WHERE age = '0' AND owner = " . $accl['id'] . " GROUP BY litterid");
	while ($lita = mysql_fetch_array($getmat))
	{
		$MsgTitle = "New Litter!";
		$Msg = "You have a new arrival in your ferretry!";

		Fez\InsertAlert($DB_Con, $lita['owner'], $Msg, $MsgTitle);
	}


	# delete any unkept kits
	$deleteoldkit = mysql_query("DELETE FROM kits WHERE age >= '6' AND owner = " . $accl['id'] . " AND keep = 0");
	$interactions = mysql_query("UPDATE kits SET interaction = 0 WHERE owner = " . $accl['id']);



// MAKE HUNGER, THIRST, HYGIENE, AND BOREDOM DEDUCT BY VALUES, MAKE AGE +1 DAY, AND MAKE HEALTH OVERALL CORRECT

	$newsandbox = mysql_query("UPDATE ferrets SET fleatreat = IF((fleatreat - 1) < 0, 0, (fleatreat - 1)), age = age + 1, treat = 0, statboost = 0, pointboost = 0, sandbox = 0 WHERE owner = " . $accl['id']);

	$newvac = mysql_query("UPDATE ferrets SET vaccinated = vaccinated - 1 WHERE vaccinated > 0 AND owner = " . $accl['id']);




// WHILE EACH FERRET DIFFERENT STATS FOR CRON

	$selfe = mysql_query("SELECT id, name, owner, hunger, thirst, boredom, cage, health, fleas, immunity, rest, incage FROM ferrets WHERE owner = " . $accl['id'] . " ORDER BY id ASC");
	while ($fer = mysql_fetch_array($selfe))
	{
		# check that the ferret has a bed
		if (empty($fer['incage']))
		{
			# not in a cage so has no bed
			$rest = mt_rand(0, 30);
		} else
		{
			# count ferrets and beds in the same cage
			$Columns = [
				[
					'Field'			 => 'id',
					'SQLFunction'	 => 'COUNT',
					'ReturnAs'		 => 'totalferrets'
				]
			];
			$Where = [
				[
					'FirstOperand'		 => 'incage',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => $fer['incage']
				]
			];

			try {
				$DB_Con->SetTable('ferrets')
					->SetColumns($Columns)
					->SetWhere($Where);
				$Result = $DB_Con->Query('SELECT', 'Standard');
				$FerretCheck = $DB_Con->FetchResults($Result, "assoc");
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
				$DB_Con->ResetQuery();
			}

			$Columns = [
				[
					'Field'			 => 'id',
					'SQLFunction'	 => 'COUNT',
					'ReturnAs'		 => 'totalbeds'
				]
			];
			$Where = [
				[
					'FirstOperand'		 => 'incage',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => $fer['incage']
				],
				[
					'ClauseOperator'	 => 'AND',
					'FirstOperand'		 => 'owner',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => $accl['id']
				],
				[
					'ClauseOperator'	 => 'AND',
					'FirstOperand'		 => 'category',
					'ExpressionOperator' => '=',
					'SecondOperand'		 => 'bed'
				]
			];

			try {
				$DB_Con->SetTable('items')
					->SetColumns($Columns)
					->SetWhere($Where);
				$Result = $DB_Con->Query('SELECT', 'Standard');
				$BedCheck = $DB_Con->FetchResults($Result, "assoc");
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
				$DB_Con->ResetQuery();
			}

			if ($BedCheck['totalbeds'] == 0)
			{
				# has no bed guaranteed lousy sleep all day/night
				$rest = mt_rand(0, 30);
			} elseif ($BedCheck['totalbeds'] >= $FerretCheck['totalferrets'])
			{
				# guaranteed plenty of good sleep all day/night
				$rest = mt_rand(75, 100);
			} else
			{
				$rest = mt_rand(31, 74);
			}
		}

		$immunity = Fez\DetermineImmunity($fer['health']);
		$hunger = mt_rand(25, 35);
		$thirst = mt_rand(35, 45);
		$boredom = mt_rand(35, 45);
		$hygiene = mt_rand(40, 50);
		$appearance = mt_rand(10, 20);

// DECIDE IF A FERRET GETS FLEAS
// WORK OUT IF THE DAILY DECUDTION OF FLEAS SHOULD HAPPEN

		if ($fer['fleas'] < 100)
		{
			$fleas = mt_rand(5, 15);
		} else
		{
			$fleas = "0";
		}

		$minusfleas = mysql_query("UPDATE ferrets SET fleas = fleas - '" . $fleas . "' WHERE id = " . $fer['id'] . " LIMIT 1");


		if ($fer['fleas'] == 100 &&
			$fer['fleatreat'] <= 0)
		{
			# don't let ferrets that have active flea treatment get fleas
			$fleachance = mt_rand(1, 20);

			if ($fleachance == 15)
			{
// GIVE THE FERRET FLEAS

				$fleas = mt_rand(1, 20);

// ALERT OWNER OF FLEAS
				$MsgTitle = "Uh Oh! Fleas...";
				$Msg = "It appears <a href=/ferret.php?id=" . $fer['id'] . ">" . $fer['name'] . "</a> has caught fleas from somewhere! Uh oh, better get the flea treatment ready before they spread...";
				try {
					Fez\InsertAlert($DB_Con, $accl['id'], $Msg, $MsgTitle);
				} catch (Exception $e) {
					\ignore_user_abort(\TRUE);
					$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
					Fez\HandleError($error);
				}

				$fleasn = mt_rand(5, 15);
				$getfleasnew = mysql_query("UPDATE ferrets SET fleas = fleas-'$fleasn' WHERE id = " . $fer['id'] . " LIMIT 1");
			}
		}


// FIGURE OUT IF THE FLEAS SHOULD BE PASSED ON TO THE OTHER FERRETS

		if ($fer['fleas'] < 30)
		{
// CLEARLY NOT TREATED AND NEGLECTED
// UPDATE ALL FERRETS that do not have active flea treatment
// WITH THIS OWNER TO HAVE FLEAS

			$selallflea = mysql_query("SELECT id FROM ferrets WHERE owner = " . $fer['owner'] . " AND fleas = 100 AND fleatreat <= 0 AND id != " . $fer['id']);
			while ($giveflea = mysql_fetch_array($selallflea))
			{
				$fleas = mt_rand(5, 15);

// UPDATE ALL THE FERRETS WITH MINUS FLEAS
				$minusfleas = mysql_query("UPDATE ferrets SET fleas = fleas-'$fleas' WHERE id = " . $giveflea['id'] . " LIMIT 1");
			}
// ALERT OF FLEAS
			$MsgTitle = "Uh Oh! More fleas...";
			$Msg = "It appears your entire business has caught fleas from <a href=/ferret.php?id=" . $fer['id'] . ">" . $fer['name'] . "</a>! If " . $fer['name'] . " had of been treated sooner, this could have been prevented.";
			try {
				Fez\InsertAlert($DB_Con, $accl['id'], $Msg, $MsgTitle);
			} catch (Exception $e) {
				\ignore_user_abort(\TRUE);
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
				Fez\HandleError($error);
			}
		}
// END OF FLEA FUNCTIONS

		$sql = "UPDATE ferrets SET hunger = IF((hunger - " . $hunger . ") < 0, 0, (hunger - " . $hunger . ")), thirst = IF((thirst - " . $thirst . ") < 0, 0, (thirst - " . $thirst . ")), boredom = IF((boredom - " . $boredom . ") < 0, 0, (boredom - " . $boredom . ")), cage = IF((cage - " . $hygiene . ") < 0, 0, (cage - " . $hygiene . ")), appearance = IF((appearance - " . $appearance . ") < 0, 0, (appearance - " . $appearance . ")), immunity = " . $immunity . ", rest = " . $rest . " WHERE id = " . $fer['id'] . " LIMIT 1";
		$newstat = mysql_query($sql);

// UPDATE HEALTH OF FERRETS

		$gnf = mysql_query("SELECT * FROM ferrets WHERE id = " . $fer['id'] . " LIMIT 1");
		$fuh = mysql_fetch_array($gnf);

		$newh = Fez\DetermineHealth($fuh);
		$hlthn = mysql_query("UPDATE ferrets SET health = " . $newh . " WHERE id = " . $fer['id'] . " LIMIT 1");
	}

	# subtract uses from their beds that are in cages
	$sql = "SELECT id"
		. " FROM items"
		. " WHERE category = 'cage' AND owner = " . $accl['id'] . " AND intrailor = 1";
	$getcagesresult = mysql_query($sql);
	while ($getcages = mysql_fetch_assoc($getcagesresult))
	{
		# count ferrets and beds in the same cage
		$Columns = [
			[
				'Field'			 => 'id',
				'SQLFunction'	 => 'COUNT',
				'ReturnAs'		 => 'totalferrets'
			]
		];
		$Where = [
			[
				'FirstOperand'		 => 'incage',
				'ExpressionOperator' => '=',
				'SecondOperand'		 => $getcages['id']
			]
		];

		try {
			$DB_Con->SetTable('ferrets')
				->SetColumns($Columns)
				->SetWhere($Where);
			$Result = $DB_Con->Query('SELECT', 'Standard');
			$FerretCheck = $DB_Con->FetchResults($Result, "assoc");
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
			$DB_Con->ResetQuery();
		}

		# if there are no ferrets in the cage, go to the next cage
		if (empty($FerretCheck['totalferrets']))
		{
			continue;
		}

		$Columns = [
			[
				'Field'			 => 'id',
				'SQLFunction'	 => 'COUNT',
				'ReturnAs'		 => 'totalbeds'
			]
		];
		$Where = [
			[
				'FirstOperand'		 => 'incage',
				'ExpressionOperator' => '=',
				'SecondOperand'		 => $getcages['id']
			],
			[
				'ClauseOperator'	 => 'AND',
				'FirstOperand'		 => 'owner',
				'ExpressionOperator' => '=',
				'SecondOperand'		 => $accl['id']
			],
			[
				'ClauseOperator'	 => 'AND',
				'FirstOperand'		 => 'category',
				'ExpressionOperator' => '=',
				'SecondOperand'		 => 'bed'
			]
		];

		try {
			$DB_Con->SetTable('items')
				->SetColumns($Columns)
				->SetWhere($Where);
			$Result = $DB_Con->Query('SELECT', 'Standard');
			$BedCheck = $DB_Con->FetchResults($Result, "assoc");
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
			$DB_Con->ResetQuery();
		}

		# subtract a use from one bed per ferret in the cage
		if ($BedCheck['totalbeds'] == $FerretCheck['totalferrets'])
		{
			$MathSubtract = 1;
			$BedLimit = $FerretCheck['totalferrets'];
		} elseif ($BedCheck['totalbeds'] > $FerretCheck['totalferrets'])
		{
			$MathSubtract = 1;
			$BedLimit = $FerretCheck['totalferrets'];
		} else
		{
			$MathSubtract = ceil($FerretCheck['totalferrets'] / $BedCheck['totalbeds']);
			$BedLimit = $BedCheck['totalbeds'];
		}

		$Columns = [
			[
				'Field'	 => 'uses',
				'Maths'	 => ' - ' . $MathSubtract
			]
		];
		$Where = [
			[
				'FirstOperand'		 => 'category',
				'ExpressionOperator' => '=',
				'SecondOperand'		 => 'bed'
			],
			[
				'ClauseOperator'	 => 'AND',
				'FirstOperand'		 => 'incage',
				'ExpressionOperator' => '=',
				'SecondOperand'		 => $getcages['id']
			],
			[
				'ClauseOperator'	 => 'AND',
				'FirstOperand'		 => 'owner',
				'ExpressionOperator' => '=',
				'SecondOperand'		 => $accl['id']
			]
		];

		try {
			$DB_Con->SetTable('items')
				->SetColumns($Columns)
				->SetWhere($Where)
				->SetLimit($BedLimit);
			$Result2 = $DB_Con->Query('UPDATE', 'Standard');
			if ($Result2 === FALSE)
			{
				$error = __FILE__ . " " . __LINE__ . PHP_EOL . 'Failed to update bed uses.';
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
	}
}

echo 'Successfully ran the daily ferret cron!';
