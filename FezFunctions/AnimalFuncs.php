<?php

# AnimalFuncs.php
# by Nicole Ward
# <http://snowwolfegames.com>
#
# Copyright (c) 2008 - 2014 - SnowWolfe Games, LLC
#
# this script contains basic animal utility functions
#

namespace Fez;

function setGender()
{
	$genders = array(
		"Jill",
		"Hob");
	$RandGender = \array_rand($genders);
	$gender = $genders[$RandGender];

	return $gender;
}


function setRandTemperament()
{
	$temperaments = [
		"Melancholic",
		"Choleric",
		"Phlegmatic",
		"Sanguine"
	];
	$RandTemp = \array_rand($temperaments);
	$temperament = $temperaments[$RandTemp];

	return $temperament;
}


function InheritTemperament($Sire, $Dam)
{
	$rand = mt_rand(1, 100);
	if ($rand <= 40)
	{
		$temperament = $Sire;
	} elseif ($rand >= 41 &&
		$rand <= 80)
	{
		$temperament = $Dam;
	} else
	{
		$temperament = setRandTemperament();
	}

	return $temperament;
}


function setBirthWeight($gender)
{
	if ($gender == 'Jill' ||
		$gender == 'Sprite')
	{
		$weight = \round(\mt_rand(45, 140) / 10, 1);
	} else
	{
		$weight = \round(\mt_rand(50, 150) / 10, 1);
	}

	return $weight;
}


function setAdultWeight($gender)
{
	if ($gender == 'Jill' ||
		$gender == 'Sprite')
	{
		$weight = \round(\mt_rand(75, 250) / 100, 1);
	} else
	{
		$weight = \mt_rand(15, 45) / 10;
	}

	return $weight;
}


function setRandColorGenes()
{
	for ($i = 0; $i < 16; $i++)
	{
		$GeneRands[] = \mt_rand(1, 100);
	}

	$ColorGenes['Agouti']['1'] = gene_threeallelesB($GeneRands['0']);
	$ColorGenes['Agouti']['2'] = gene_threeallelesB($GeneRands['1']);
	$ColorGenes['Albino']['1'] = gene_threeallelesA($GeneRands['2']);
	$ColorGenes['Albino']['2'] = gene_threeallelesA($GeneRands['3']);
	$ColorGenes['Grey']['1'] = gene_third($GeneRands['4']);
	$ColorGenes['Grey']['2'] = gene_five($GeneRands['5']);
	$ColorGenes['Hair']['1'] = gene_ninetyfive($GeneRands['6']);
	$ColorGenes['Hair']['2'] = gene_ninetyeight($GeneRands['7']);
	$ColorGenes['Mitt']['1'] = gene_threequarters($GeneRands['8']);
	$ColorGenes['Mitt']['2'] = gene_threequarters($GeneRands['9']);
	$ColorGenes['Pointed']['1'] = gene_threequarters($GeneRands['10']);
	$ColorGenes['Pointed']['2'] = gene_ninetyfive($GeneRands['11']);
	$ColorGenes['Waard']['1'] = gene_two($GeneRands['12']);
	$ColorGenes['Waard']['2'] = gene_two($GeneRands['13']);
	$ColorGenes['White']['1'] = gene_ninetyfive($GeneRands['14']);
	$ColorGenes['White']['2'] = gene_ninetyeight($GeneRands['15']);

	return $ColorGenes;
}


function InheritColorGenes($SireGenes, $DamGenes)
{
	$AgSire = ChooseChromosome();
	$AgDam = ChooseChromosome();
	$AlSire = ChooseChromosome();
	$AlDam = ChooseChromosome();
	$GSire = ChooseChromosome();
	$GDam = ChooseChromosome();
	$HSire = ChooseChromosome();
	$HDam = ChooseChromosome();
	$MSire = ChooseChromosome();
	$MDam = ChooseChromosome();
	$PSire = ChooseChromosome();
	$PDam = ChooseChromosome();
	$WaSire = ChooseChromosome();
	$WaDam = ChooseChromosome();
	$WhSire = ChooseChromosome();
	$WhDam = ChooseChromosome();
	$ColorGenes['Agouti']['1'] = $SireGenes['Agouti'][$AgSire['ChromosomeChosen']];
	$ColorGenes['Agouti']['2'] = $DamGenes['Agouti'][$AgDam['ChromosomeChosen']];
	$ColorGenes['Albino']['1'] = $SireGenes['Albino'][$AlSire['ChromosomeChosen']];
	$ColorGenes['Albino']['2'] = $DamGenes['Albino'][$AlDam['ChromosomeChosen']];
	$ColorGenes['Grey']['1'] = $SireGenes['Grey'][$GSire['ChromosomeChosen']];
	$ColorGenes['Grey']['2'] = $DamGenes['Grey'][$GDam['ChromosomeChosen']];
	$ColorGenes['Hair']['1'] = $SireGenes['Hair'][$HSire['ChromosomeChosen']];
	$ColorGenes['Hair']['2'] = $DamGenes['Hair'][$HDam['ChromosomeChosen']];
	$ColorGenes['Mitt']['1'] = $SireGenes['Mitt'][$MSire['ChromosomeChosen']];
	$ColorGenes['Mitt']['2'] = $DamGenes['Mitt'][$MDam['ChromosomeChosen']];
	$ColorGenes['Pointed']['1'] = $SireGenes['Pointed'][$PSire['ChromosomeChosen']];
	$ColorGenes['Pointed']['2'] = $DamGenes['Pointed'][$PDam['ChromosomeChosen']];
	$ColorGenes['Waard']['1'] = $SireGenes['Waard'][$WaSire['ChromosomeChosen']];
	$ColorGenes['Waard']['2'] = $DamGenes['Waard'][$WaDam['ChromosomeChosen']];
	$ColorGenes['White']['1'] = $SireGenes['White'][$WhSire['ChromosomeChosen']];
	$ColorGenes['White']['2'] = $DamGenes['White'][$WhDam['ChromosomeChosen']];

	return $ColorGenes;
}


function getColor($ColorGenes)
{
	$basecolor = '';

	if ($ColorGenes['Albino']['1'] == '3' &&
		$ColorGenes['Albino']['2'] == '3')
	{
		$basecolor = 'Albino';
	} elseif ($ColorGenes['White']['1'] == '2' &&
		$ColorGenes['White']['2'] == '2')
	{
		$basecolor = 'Dark-Eyed White';
	} else
	{
		if ($ColorGenes['Albino']['1'] == '2' &&
			$ColorGenes['Albino']['2'] == '2')
		{
			$basecolor = 'Chocolate';
		} else
		{
			if ($ColorGenes['Agouti']['1'] == '3' &&
				$ColorGenes['Agouti']['2'] == '3')
			{
				$basecolor = 'Black';
			} elseif ($ColorGenes['Agouti']['1'] == '1' ||
				$ColorGenes['Agouti']['2'] == '1')
			{
				$basecolor = 'Sable';
			} else
			{
				$basecolor = 'Black Sable';
			}

			if (($ColorGenes['Albino']['1'] == '2' &&
				$ColorGenes['Albino']['2'] == '3') ||
				($ColorGenes['Albino']['1'] == '3' &&
				$ColorGenes['Albino']['2'] == '2'))
			{
				if ($basecolor == 'Sable')
				{
					$basecolor = 'Champagne';
				} else
				{
					$basecolor = 'Cinnamon';
				}
			}
		}

		if ($ColorGenes['Grey']['1'] == '1' ||
			$ColorGenes['Grey']['2'] == '1')
		{
			$basecolor .= ' Roan';
		} elseif ($ColorGenes['Pointed']['1'] == '2' &&
			$ColorGenes['Pointed']['2'] == '2')
		{
			if ($basecolor === 'Black Sable')
			{
				$basecolor = 'Sable';
			}
			$basecolor .= ' Point';
		}

		if ($ColorGenes['Waard']['1'] == '1' ||
			$ColorGenes['Waard']['2'] == '1')
		{
			$basecolor .= (\mt_rand(1, 2) == 1)
				? ' Blaze'
				: ' Panda';
		} elseif ($ColorGenes['Mitt']['1'] == '2' &&
			$ColorGenes['Mitt']['2'] == '2')
		{
			$basecolor .= ' Mitt';
		}

		if ($ColorGenes['Hair']['1'] == '2' &&
			$ColorGenes['Hair']['2'] == '2')
		{
			$basecolor .= ' Angora';
		}
	}

	return $basecolor;
}


function setRandTraitGenes()
{
	for ($i = 1; $i < 11; $i++)
	{
		$GeneRands[$i] = \mt_rand(1, 10);
	}

	return $GeneRands;
}


function InheritTraitGenes($SireGenes, $DamGenes, $Trait)
{
	foreach ($SireGenes as $Key => $Value)
	{
		if (stripos($Key, $Trait) !== FALSE)
		{
			$Sire = ChooseChromosome();
			$Dam = ChooseChromosome();
			$TraitGenes[$Key]['1'] = $Value[$Sire['ChromosomeChosen']];
			$TraitGenes[$Key]['2'] = $DamGenes[$Key][$Dam['ChromosomeChosen']];
		}
	}

	return $TraitGenes;
}


function InheritTraitStats($SireStat, $DamStat, $StartingStat, $TraitValue)
{
	$TraitValue /= 100;
	$SireStat *= $TraitValue;
	$DamStat *= $TraitValue;
	$Stat = $StartingStat + (($SireStat + $DamStat) / 2);

	return $Stat;
}


function setRandEyesight($Color)
{
	$eyesight = 40;

	if ($Color === 'Albino')
	{
		$eyesight = \mt_rand(1, 35);
	} else
	{
		$eyesight = \mt_rand(1, 100);
	}

	return $eyesight;
}


function InheritEyesight($Color, $Sire, $Dam)
{
	if ($Color === 'Albino')
	{
		$eyesight = \mt_rand(1, 35);
	} else
	{
		$eyesight = ($Sire + $Dam) / 2;
	}

	return $eyesight;
}


function setRandHearing($Color)
{
	$hearing = 40;

	if (\strripos($Color, 'Blaze') ||
		\strripos($Color, 'Panda'))
	{
		if (\mt_rand(1, 4) == 4)
		{
			$hearing = \mt_rand(1, 100);
		} else
		{
			$hearing = 0;
		}
	} else
	{
		$hearing = \mt_rand(1, 100);
	}

	return $hearing;
}


function InheritHearing($Color, $Sire, $Dam)
{
	$hearing = -1;

	if (\strripos($Color, 'Blaze') ||
		\strripos($Color, 'Panda'))
	{
		if (\mt_rand(1, 4) < 4)
		{
			$hearing = 0;
		}
	}
	if ($hearing === -1)
	{
		$hearing = ($Sire + $Dam) / 2;
	}

	return $hearing;
}


function getImage($Color)
{

	if ($Color == 'Albino')
	{
		$image = ("/ferretimages/albino.png");
	} else
	{
		if ($Color == 'Silver')
		{
			$image = ("/ferretimages/silver.png");
		} else
		{

			if (($Color == 'Black Sable Roan') OR ( $Color == 'Black Sable Standard') OR ( $Color == 'Black Sable Blaze') OR ( $Color == 'Black Sable Solid') OR ( $Color == 'Black Sable Point') OR ( $Color == 'Black Sable Panda'))
			{

				$image = ("/ferretimages/blacksable.png");
			} else
			{
				list($output) = explode(' ', trim($Color));
				//$output = strstr($Color, ' ', true);

				if ($output == 'Black')
				{
					$image = ("/ferretimages/black.png");
				}
				if ($output == 'Champagne')
				{
					$image = ("/ferretimages/champagne.png");
				}
				if ($output == 'Chocolate')
				{
					$image = ("/ferretimages/chocolate.png");
				}
				if ($output == 'Cinnamon')
				{
					$image = ("/ferretimages/cinnamon.png");
				}
				if ($output == 'Dark-Eyed')
				{
					$image = ("/ferretimages/DEW.png");
				}
				if ($output == 'Sable')
				{
					$image = ("/ferretimages/sable.png");
				}
			}
		}
	}

	return $image;
}


function getPointReqs($Bracket)
{
	/* 	switch ($Bracket) {
	  case 'Local':
	  $Reqs = [
	  'OnePoint'	 => 5,
	  'TwoPoint'	 => 15
	  ];
	  break;
	  case 'State':
	  $Reqs = [
	  'OnePoint'	 => 10,
	  'TwoPoint'	 => 20
	  ];
	  break;
	  case 'National':
	  $Reqs = [
	  'OnePoint'	 => 15,
	  'TwoPoint'	 => 30
	  ];
	  break;
	  case 'World':
	  $Reqs = [
	  'OnePoint'	 => 20,
	  'TwoPoint'	 => 40
	  ];
	  break;
	  default:
	  $Reqs = \FALSE;
	  break;
	  } */

	switch ($Bracket) {
		case 'Local':
			$Reqs = [
				'OnePoint'	 => 2,
				'TwoPoint'	 => 5
			];
			break;
		case 'State':
			$Reqs = [
				'OnePoint'	 => 3,
				'TwoPoint'	 => 7
			];
			break;
		case 'National':
			$Reqs = [
				'OnePoint'	 => 4,
				'TwoPoint'	 => 9
			];
			break;
		case 'World':
			$Reqs = [
				'OnePoint'	 => 5,
				'TwoPoint'	 => 11
			];
			break;
		default:
			$Reqs = \FALSE;
			break;
	}

	return $Reqs;
}


function getTitleReqs($Bracket)
{
	switch ($Bracket) {
		case 'Local':
			$Reqs = [
				'Points'	 => 10,
				'Defeated'	 => 20
			];
			break;
		case 'State':
			$Reqs = [
				'Points'	 => 15,
				'Defeated'	 => 35
			];
			break;
		case 'National':
			$Reqs = [
				'Points'	 => 25,
				'Defeated'	 => 60
			];
			break;
		case 'World':
			$Reqs = [
				'Points'	 => 40,
				'Defeated'	 => 100
			];
			break;
		default:
			$Reqs = \FALSE;
			break;
	}

	return $Reqs;
}


function getNextBracket($Bracket)
{
	switch ($Bracket) {
		case 'Local':
			$NextBracket = 'State';
			break;
		case 'State':
			$NextBracket = 'National';
			break;
		case 'National':
			$NextBracket = 'World';
			break;
		case 'World':
			$NextBracket = \FALSE;
			break;
	}

	return $NextBracket;
}


function getBracketNumber($Bracket)
{
	switch ($Bracket) {
		case 'Local':
			$Number = 1;
			break;
		case 'State':
			$Number = 2;
			break;
		case 'National':
			$Number = 3;
			break;
		case 'World':
			$Number = 4;
			break;
	}

	return $Number;
}


function getAnimalLevelRange($Level)
{
	if ($Level == 0)
	{
		$LevelRange['min'] = 0;
		$LevelRange['max'] = 10;
	} elseif ($Level == 1)
	{
		$NextLevel = $Level + 1;
		$LevelRange['min'] = 11;
		$LevelRange['max'] = 25 * $NextLevel * $NextLevel - 25 * $NextLevel;
	} else
	{
		$NextLevel = $Level + 1;
		$LevelRange['min'] = (25 * $Level * $Level - 25 * $Level) + 1;
		$LevelRange['max'] = 25 * $NextLevel * $NextLevel - 25 * $NextLevel;
	}

	return $LevelRange;
}


function getAnimalLevel($XP)
{
	if ($XP < 10)
	{
		$Level = 0;
	} else
	{
		$Level = floor((25 + sqrt(25 * 25 - 4 * 25 * (-$XP))) / (2 * 25));
	}

	return $Level;
}


function getEyesightDescription($Eyesight)
{
	if ($Eyesight < 25)
	{
		$eyesightDescription = "Poor";
	} elseif ($Eyesight >= 25 &&
		$Eyesight < 50)
	{
		$eyesightDescription = "Fair";
	} elseif ($Eyesight >= 50 &&
		$Eyesight < 75)
	{
		$eyesightDescription = "Good";
	} elseif ($Eyesight >= 75)
	{
		$eyesightDescription = "Excellent";
	}

	return $eyesightDescription;
}


function getHearingDescription($Hearing)
{
	if ($Hearing == 0)
	{
		$hearingDescription = "Deaf";
	} elseif ($Hearing < 25)
	{
		$hearingDescription = "Poor";
	} elseif ($Hearing >= 25 &&
		$Hearing < 50)
	{
		$hearingDescription = "Fair";
	} elseif ($Hearing >= 50 &&
		$Hearing < 75)
	{
		$hearingDescription = "Good";
	} elseif ($Hearing >= 75)
	{
		$hearingDescription = "Excellent";
	}

	return $hearingDescription;
}


function InsertAnimal($Animal, $DB_Con)
{
	$Columns = [
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
			'Field' => 'weight'
		],
		[
			'Field' => 'hunger'
		],
		[
			'Field' => 'thirst'
		],
		[
			'Field' => 'boredom'
		],
		[
			'Field' => 'cage'
		],
		[
			'Field' => 'appearance'
		],
		[
			'Field' => 'immunity'
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
			'Field' => 'description'
		],
		[
			'Field' => 'mother'
		],
		[
			'Field' => 'father'
		],
		[
			'Field' => 'bredby'
		],
		[
			'Field' => 'owner'
		],
		[
			'Field' => 'image'
		]
	];
	$InsertVals[] = [
		[
			'Value' => $Animal['age']
		],
		[
			'Value' => $Animal['gender']
		],
		[
			'Value' => $Animal['color']
		],
		[
			'Value' => $Animal['temperament']
		],
		[
			'Value' => $Animal['weight']
		],
		[
			'Value' => $Animal['hunger']
		],
		[
			'Value' => $Animal['thirst']
		],
		[
			'Value' => $Animal['boredom']
		],
		[
			'Value' => $Animal['cagehygiene']
		],
		[
			'Value' => $Animal['appearance']
		],
		[
			'Value' => $Animal['immunity']
		],
		[
			'Value' => $Animal['rest']
		],
		[
			'Value' => $Animal['GenStamina']
		],
		[
			'Value' => $Animal['GenStrength']
		],
		[
			'Value' => $Animal['GenSpeed']
		],
		[
			'Value' => $Animal['GenIntelligence']
		],
		[
			'Value' => $Animal['GenAgility']
		],
		[
			'Value' => $Animal['GenCoat']
		],
		[
			'Value' => $Animal['GenConformation']
		],
		[
			'Value' => $Animal['Stamina']
		],
		[
			'Value' => $Animal['Strength']
		],
		[
			'Value' => $Animal['Speed']
		],
		[
			'Value' => $Animal['Intelligence']
		],
		[
			'Value' => $Animal['Agility']
		],
		[
			'Value' => $Animal['Coat']
		],
		[
			'Value' => $Animal['health']
		],
		[
			'Value' => $Animal['eyesight']
		],
		[
			'Value' => $Animal['hearing']
		],
		[
			'Value' => $Animal['desc']
		],
		[
			'Value' => $Animal['mother']
		],
		[
			'Value' => $Animal['father']
		],
		[
			'Value' => $Animal['breeder']
		],
		[
			'Value' => $Animal['owner']
		],
		[
			'Value' => $Animal['image']
		]
	];


	try {
		$DB_Con->SetTable('ferrets')
			->SetColumns($Columns)
			->SetInsertValues($InsertVals);
		$Result = $DB_Con->Query('INSERT', 'Standard');
		if ($Result === FALSE)
		{
			\ignore_user_abort(\TRUE);
			$UserMsg = 'There was an error processing your purchase. The error has been logged. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error, $UserMsg, 'error.php');
		}
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
}


function InsertOffspring($Offspring, $DB_Con)
{
	$Columns = [
		[
			'Field' => 'name'
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
			'Field' => 'Agouti1'
		],
		[
			'Field' => 'Agouti2'
		],
		[
			'Field' => 'Albino1'
		],
		[
			'Field' => 'Albino2'
		],
		[
			'Field' => 'Grey1'
		],
		[
			'Field' => 'Grey2'
		],
		[
			'Field' => 'Hair1'
		],
		[
			'Field' => 'Hair2'
		],
		[
			'Field' => 'Mitt1'
		],
		[
			'Field' => 'Mitt2'
		],
		[
			'Field' => 'Pointed1'
		],
		[
			'Field' => 'Pointed2'
		],
		[
			'Field' => 'Waard1'
		],
		[
			'Field' => 'Waard2'
		],
		[
			'Field' => 'White1'
		],
		[
			'Field' => 'White2'
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
			'Field' => 'StaminaGenes'
		],
		[
			'Field' => 'StrengthGenes'
		],
		[
			'Field' => 'SpeedGenes'
		],
		[
			'Field' => 'IntelligenceGenes'
		],
		[
			'Field' => 'AgilityGenes'
		],
		[
			'Field' => 'CoatGenes'
		],
		[
			'Field' => 'ConformationGenes'
		],
		[
			'Field' => 'eyesight'
		],
		[
			'Field' => 'hearing'
		],
		[
			'Field' => 'mother'
		],
		[
			'Field' => 'father'
		],
		[
			'Field' => 'weight'
		],
		[
			'Field' => 'currentweight'
		],
		[
			'Field' => 'litterid'
		],
		[
			'Field' => 'incage'
		],
		[
			'Field' => 'owner'
		],
		[
			'Field' => 'image'
		]
	];

	$InsertVals[] = [
		[
			'Value' => $Offspring['name']
		],
		[
			'Value' => $Offspring['age']
		],
		[
			'Value' => $Offspring['gender']
		],
		[
			'Value' => $Offspring['color']
		],
		[
			'Value' => $Offspring['temperament']
		],
		[
			'Value' => $Offspring['Agouti1']
		],
		[
			'Value' => $Offspring['Agouti2']
		],
		[
			'Value' => $Offspring['Albino1']
		],
		[
			'Value' => $Offspring['Albino2']
		],
		[
			'Value' => $Offspring['Grey1']
		],
		[
			'Value' => $Offspring['Grey2']
		],
		[
			'Value' => $Offspring['Hair1']
		],
		[
			'Value' => $Offspring['Hair2']
		],
		[
			'Value' => $Offspring['Mitt1']
		],
		[
			'Value' => $Offspring['Mitt2']
		],
		[
			'Value' => $Offspring['Pointed1']
		],
		[
			'Value' => $Offspring['Pointed2']
		],
		[
			'Value' => $Offspring['Waard1']
		],
		[
			'Value' => $Offspring['Waard2']
		],
		[
			'Value' => $Offspring['White1']
		],
		[
			'Value' => $Offspring['White2']
		],
		[
			'Value' => $Offspring['geneticstamina']
		],
		[
			'Value' => $Offspring['geneticstrength']
		],
		[
			'Value' => $Offspring['geneticspeed']
		],
		[
			'Value' => $Offspring['geneticintelligence']
		],
		[
			'Value' => $Offspring['geneticagility']
		],
		[
			'Value' => $Offspring['geneticcoat']
		],
		[
			'Value' => $Offspring['geneticconformation']
		],
		[
			'Value' => $Offspring['stamina']
		],
		[
			'Value' => $Offspring['strength']
		],
		[
			'Value' => $Offspring['speed']
		],
		[
			'Value' => $Offspring['intelligence']
		],
		[
			'Value' => $Offspring['agility']
		],
		[
			'Value' => $Offspring['coat']
		],
		[
			'Value' => $Offspring['StaminaGenes']
		],
		[
			'Value' => $Offspring['StrengthGenes']
		],
		[
			'Value' => $Offspring['SpeedGenes']
		],
		[
			'Value' => $Offspring['IntelligenceGenes']
		],
		[
			'Value' => $Offspring['AgilityGenes']
		],
		[
			'Value' => $Offspring['CoatGenes']
		],
		[
			'Value' => $Offspring['ConformationGenes']
		],
		[
			'Value' => $Offspring['eyesight']
		],
		[
			'Value' => $Offspring['hearing']
		],
		[
			'Value' => $Offspring['mother']
		],
		[
			'Value' => $Offspring['father']
		],
		[
			'Value' => $Offspring['weight']
		],
		[
			'Value' => $Offspring['currentweight']
		],
		[
			'Value' => $Offspring['litterid']
		],
		[
			'Value' => $Offspring['incage']
		],
		[
			'Value' => $Offspring['owner']
		],
		[
			'Value' => $Offspring['image']
		]
	];


	try {
		$DB_Con->SetTable('kits')
			->SetColumns($Columns)
			->SetInsertValues($InsertVals);
		$Result = $DB_Con->Query('INSERT', 'Standard');
		if ($Result === FALSE)
		{
			\ignore_user_abort(\TRUE);
			$UserMsg = 'There was an error processing your purchase. The error has been logged. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error, $UserMsg, 'error.php');
		}
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
}


function InsertGenes($AnimalID, $Genes, $DB_Con)
{
	$DB_Con->AutoCommit(FALSE);
	$Columns = [
		[
			'Field' => 'AnimalID'
		],
		[
			'Field' => 'ChromosomeID'
		],
		[
			'Field' => 'GeneID'
		],
		[
			'Field' => 'AlleleValue'
		]
	];
	foreach ($Genes AS $Gene => $Chromosome)
	{
		foreach ($Chromosome as $key => $value)
		{
			$InsertVals[] = [
				[
					'Value' => $AnimalID
				],
				[
					'Value' => $key
				],
				[
					'Value' => $Gene
				],
				[
					'Value' => $value
				]
			];
		}
	}


	try {
		$DB_Con->SetTable('Animals_DNA')
			->SetColumns($Columns)
			->SetInsertValues($InsertVals);
		$Result = $DB_Con->Query('INSERT', 'Standard');
		if ($Result === FALSE)
		{
			\ignore_user_abort(\TRUE);
			$DB_Con->RollbackTrans();
			$UserMsg = 'There was an error processing your purchase. The error has been logged. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
			$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
			Fez\HandleError($error, $UserMsg, 'error.php');
		}
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

	$DB_Con->CommitTrans();
	$DB_Con->AutoCommit(TRUE);
}


function RoundTraits($Trait)
{
	# this will round the inherited traits
	switch (true) {
		case $Trait >= 9.76:
			$result = 'Extraordinary';
			break;
		case $Trait >= 9.01:
			$result = 'Excellent';
			break;
		case $Trait >= 8.11:
			$result = 'Extremely Good';
			break;
		case $Trait >= 7.01:
			$result = 'Very Good';
			break;
		case $Trait >= 5.635:
			$result = 'Good';
			break;
		case $Trait >= 4.26:
			$result = 'Average';
			break;
		case $Trait >= 3.16:
			$result = 'Fair';
			break;
		case $Trait >= 2.26:
			$result = 'Poor';
			break;
		case $Trait >= 1.51:
			$result = 'Very Poor';
			break;
		case $Trait >= 1.00:
			$result = 'Extremely Poor';
			break;
		default:
			$result = 'Minimal';
			break;
	}

	return $result;
}


function FeedBaseStatGain($Feed)
{
	switch ($Feed) {
		case 'Premium Feast':
			$Return['Stamina'] = mt_rand(2, 5);
			$Return['Strength'] = mt_rand(2, 5);
			$Return['Speed'] = mt_rand(2, 5);
			$Return['Intelligence'] = mt_rand(2, 5);
			$Return['Agility'] = mt_rand(2, 5);
			$Return['Coat'] = mt_rand(2, 5);
			break;
		case 'Basic Feast':
		default:
			$Return['Stamina'] = mt_rand(0, 2);
			$Return['Strength'] = mt_rand(0, 2);
			$Return['Speed'] = mt_rand(0, 2);
			$Return['Intelligence'] = mt_rand(0, 2);
			$Return['Agility'] = mt_rand(0, 2);
			$Return['Coat'] = mt_rand(0, 2);
			break;
	}

	return $Return;
}


function ScoreEvents_Class($Animal, $Class)
{
	$ClassesInfo = [
		'Conformation'	 => [
			'Temperament'	 => [
				'Sanguine'
			],
			'FitnessTraits'	 => [
				'coat',
				'intelligence'
			],
			'ScoreWeights'	 => [
				'conformation'	 => 25,
				'temperament'	 => 25,
				'coat'			 => 20,
				'intelligence'	 => 10,
				'weight'		 => 15,
				'eyesight'		 => 2.5,
				'hearing'		 => 2.5
			]
		],
		'Racing'		 => [
			'Temperament'	 => [
				'Choleric'
			],
			'FitnessTraits'	 => [
				'speed',
				'stamina',
				'strength'
			],
			'ScoreWeights'	 => [
				'conformation'	 => 20,
				'temperament'	 => 15,
				'speed'			 => 25,
				'stamina'		 => 20,
				'strength'		 => 15,
				'syesight'		 => 2.5,
				'hearing'		 => 2.5
			]
		],
		'Agility'		 => [
			'Temperament'	 => [
				'Phlegmatic'
			],
			'FitnessTraits'	 => [
				'agility',
				'speed',
				'intelligence'
			],
			'ScoreWeights'	 => [
				'geneticconformation'	 => 20,
				'temperament'			 => 15,
				'agility'				 => 25,
				'speed'					 => 20,
				'intelligence'			 => 15,
				'eyesight'				 => 2.5,
				'hearing'				 => 2.5
			]
		],
		'Hunting'		 => [
			'Temperament'	 => [
				'Phlegmatic',
				'Choleric'
			],
			'FitnessTraits'	 => [
				'strength',
				'stamina',
				'intelligence'
			],
			'ScoreWeights'	 => [
				'geneticconformation'	 => 20,
				'temperament'			 => 15,
				'strength'				 => 25,
				'stamina'				 => 20,
				'intelligence'			 => 15,
				'eyesight'				 => 2.5,
				'hearing'				 => 2.5
			]
		]
	];

	$ClassInfo = $ClassesInfo[$Class];

	# loop through the $ClassInfo['Traits'] array
	# and multiply their fitness for that trait
	# by the event class importance percent
	$ScoresKeys = \array_keys($ClassInfo['ScoreWeights']);
	$Scores = \array_flip($ScoresKeys);

	# score conformation
	$Scores['conformation'] = $Animal['geneticconformation'] * $ClassInfo['ScoreWeights']['conformation'];

	# score temperament
	if (\in_array($Animal['temperament'], $ClassInfo['temperament']))
	{
		$Scores['temperament'] = 10 * $ClassInfo['ScoreWeights']['temperament'];
	} elseif ($Animal['temperament'] == 'Melancholic')
	{
		$Scores['temperament'] = -1 * $ClassInfo['ScoreWeights']['temperament'];
	} else
	{
		$Scores['temperament'] = 0;
	}

	# score eyesight and hearing
	$Scores['eyesight'] = $Animal['eyesight'] / 10 * $ClassInfo['ScoreWeights']['eyesight'];
	$Scores['hearing'] = $Animal['hearing'] / 10 * $ClassInfo['ScoreWeights']['hearing'];

	# score each 'fitness' trait
	foreach ($ClassInfo['FitnessTraits'] as $Trait)
	{
		$GeneticTrait = 'genetic' . $Trait;
		$Scores[$Trait] += ($Animal[$Trait] * ($Animal[$GeneticTrait] / 100)) * $ClassInfo['ScoreWeights'][$Trait];
	}

	# if weight is a factor, score it
	if (\array_key_exists('weight', $ClassInfo['ScoreWeights']))
	{
		switch ($Animal['gender']) {
			case 'Hob':
			case 'Gib':
				$IdealRange = [
					'min'	 => 2,
					'max'	 => 4
				];
				break;
			default:
				$IdealRange = [
					'min'	 => 1,
					'max'	 => 2
				];
				break;
		}

		if ($Animal['weight'] < $IdealRange['min'])
		{
			$Scores['weight'] = (10 - ($IdealRange['min'] - $Animal['weight'])) * $ClassInfo['ScoreWeights']['weight'];
		} elseif ($Animal['weight'] > $IdealRange['max'])
		{
			$Scores['weight'] = (10 - ($Animal['weight'] - $IdealRange['min'])) * $ClassInfo['ScoreWeights']['weight'];
		} else
		{
			$Scores['weight'] = 10 * $ClassInfo['ScoreWeights']['weight'];
		}
	} else
	{
		$Scores['weight'] = 0;
	}

	# add some randomization
	$Scores['rand'] = \mt_rand(100, 1000) / 100;
	$BaseScore = \array_sum($Scores);

	/*
	  # give bonus to neutered horses
	  if ($this->Data['sex'] == 'Gelding' ||
	  $this->Data['sex'] == 'Sterile Mare')
	  {
	  $GenderBonus = (mt_rand(1, 5) / 100) + 1;
	  } else
	  {
	  $GenderBonus = 1;
	  }
	 */

	# apply rest factor
	$BaseScore = $BaseScore * ($Animal['rest'] / 100);

	# apply age factor to score
	switch (true) {
		case $Animal['age'] > 336:
			$Score = $BaseScore * 0.15;
			break;
		case $Animal['age'] > 330:
			$Score = $BaseScore * 0.1;
			break;
		case $Animal['age'] > 320:
			$Score = $BaseScore * 0.075;
			break;
		default:
			// nothing to do, the animal is of prime age
			$Score = $BaseScore;
			break;
	}

	return $Score;
}


function DetermineHealth($Animal)
{
	$SumStats = $Animal['hunger'] + $Animal['thirst'] + $Animal['boredom'] + $Animal['cage'] + $Animal['appearance'] + $Animal['fleas'] + $Animal['immunity'] + $Animal['rest'];

	return ceil($SumStats / 8);
}


function DetermineImmunity($health)
{
	if ($health > 75)
	{
		$immunity = mt_rand(75, 100);
	} elseif ($health > 50 &&
		$health < 75)
	{
		$immunity = mt_rand(50, 75);
	} elseif ($health > 25 &&
		$health < 50)
	{
		$immunity = mt_rand(25, 50);
	} elseif ($health < 25)
	{
		$immunity = mt_rand(0, 25);
	}

	return $immunity;
}


function getDNA($AnimalID, $DB_Con)
{
	$Columns = [
		[
			'Field' => 'ChromosomeID'
		],
		[
			'Field' => 'GeneID'
		],
		[
			'Field' => 'AlleleValue'
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'AnimalID',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $AnimalID
		]
	];

	try {
		$DB_Con->SetTable(
				'Animals_DNA')
			->SetColumns(
				$Columns)
			->SetWhere(
				$Where);
		$Result = $DB_Con->Query(
			'SELECT', 'Standard');
		while (
		$DNA = $DB_Con->FetchResults(
		$Result, "object"))
		{
			$Genetics[
				$DNA->GeneID] [$DNA->ChromosomeID] = $DNA->AlleleValue;
		}
		$DB_Con->CloseResult();
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

	return $Genetics;

	# end getDNA()
}


function

setDNANotation($Genetics, $DB_Con)
{
	$DNANotation = array();
	$GenesArr = [
		"Agouti",
		"Albino",
		"Grey",
		"Mitt",
		"Pointed",
		"Waard",
		"White"
	];
	$Columns = [
		[
			'Field' => 'DNACode'
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'GeneKey',
			'ExpressionOperator' => '='
		],
		[
			'ClauseOperator'	 => 'AND',
			'FirstOperand'		 => 'GeneSequence',
			'ExpressionOperator' => '='
		]
	];

	try {
		$DB_Con->SetTable(
				'GameOps_DNASequences')
			->SetColumns(
				$Columns)
			->SetWhere(
				$Where)
			->SetLimit(
				1);
		$DB_Con->Query(
			'SELECT', 'Prepared');
	} catch (
	\InvalidArgumentException $e) {
		$DB_Con->ResetQuery();
		throw $e;
	} catch (
	\NoResultException $e) {
		$DB_Con->ResetQuery();
		throw $e;
	} catch (
	\DBException $e) {
		$DB_Con->ResetQuery();
		throw $e;
	} catch (
	\Exception $e) {
		$DB_Con->ResetQuery();
		throw $e;
	}
	foreach (
	$GenesArr AS $Value)
	{
		$Params = [
			$Value,
			$Genetics[$Value][
			'1'] . $Genetics [$Value]['2']
		];
		try {
			$DB_Con->SetInputParams($Params)
				->BindInputParams()
				->ExecutePreparedQuery();
			$GeneNotation = $DB_Con->FetchPreparedResults("object");
		} catch (\InvalidArgumentException $e) {
			$DB_Con->ResetQuery();
			throw $e;
		} catch (\NoResultException $e) {
			$DB_Con->ResetQuery();
			throw $e;
		} catch (\DBException $e) {
			$DB_Con->ResetQuery();
			throw $e;
		} catch (\Exception $e) {
			$DB_Con->ResetQuery();
			throw $e;
		}

		$DNANotation[$Value] = $GeneNotation->DNACode;
	}

	$DB_Con->CloseStmt()
		->ResetQuery();

	return $DNANotation;

	# end setDNANotation()
}

