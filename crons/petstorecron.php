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

echo 'begin pet store<br />';

require_once 'settings.php';
require_once Fez\INC_ROOT . 'configure.php';
$t1 = new Fez\code_timer;
ini_set('display_errors', 'On');

require_once Fez\FUNC_ROOT . 'GeneFuncs.php';
require_once Fez\FUNC_ROOT . 'AnimalFuncs.php';

$dbhost = 'swgdb.cgnwvdsnszvj.us-east-1.rds.amazonaws.com';
$dbuser = 'fez_user';
$dbpass = 'T8rmQ6c!P$byGWA';
$dbname = 'fezgamedb';


$link = @mysql_pconnect($dbhost, $dbuser, $dbpass) or die("A link couldn't be made to the MySQL database server. Please try again. If the problem persists, please contact the server administrator.");
$selectdb = @mysql_select_db($dbname, $link) or die("The MySQL database couldn't be selected. Please try again. If the problem persists, please contact the server administrator.");


// END DB CONNECT

$deleteferrets = mysql_query("DELETE FROM petstore ORDER BY id ASC");


$amount = "4";
$name = "No Name";
$prices = [
	100,
	125,
	150,
	175,
	200,
	225,
	250,
	275,
	300,
	325,
	350
];

for ($i = 1; $i <= $amount; $i++)
{
	$age = mt_rand(6, 12);

	$gender = Fez\setGender();
	$temperament = Fez\setRandTemperament();
	$weight = Fez\setAdultWeight($gender);

	$RandPrice = array_rand($prices);
	$price = $prices[$RandPrice];

// COLOR BELOW
// genetics / colours:

	$ColorGenes = Fez\setRandColorGenes();

// GENES ANALYSE
// quick genotype analysis
	$Color = Fez\getColor($ColorGenes);

// END OF GENES ANALYSE
// IMAGE DEFINING BELOW

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

// END OF IMAGE



	$fewfer = mysql_query("INSERT INTO petstore(name, age, gender, temperament, image, color, agouti1, agouti2, albino1, albino2, grey1, grey2, hair1, hair2, mitt1, mitt2, pointed1, pointed2, waard1, waard2, white1, white2, weight, price) VALUES ('" . $name . "','" . $age . "','" . $gender . "','" . $temperament . "', '" . $image . "', '" . $Color . "', " . $ColorGenes['Agouti']['1'] . ", " . $ColorGenes['Agouti']['2'] . ", " . $ColorGenes['Albino']['1'] . ", " . $ColorGenes['Albino']['2'] . ", " . $ColorGenes['Grey']['1'] . ", " . $ColorGenes['Grey']['2'] . ", " . $ColorGenes['Hair']['1'] . ", " . $ColorGenes['Hair']['2'] . ", " . $ColorGenes['Mitt']['1'] . ", " . $ColorGenes['Mitt']['2'] . ", " . $ColorGenes['Pointed']['1'] . ", " . $ColorGenes['Pointed']['2'] . ", " . $ColorGenes['Waard']['1'] . ", " . $ColorGenes['Waard']['2'] . ", " . $ColorGenes['White']['1'] . ", " . $ColorGenes['White']['2'] . ", '" . $weight . "', '" . $price . "')")or die("Error: " . mysql_error());

	unset($image, $Color, $ColorGenes, $price, $RandPrice, $age, $weight, $temperament, $gender);
}

// END OF INSERT NEW FERRET INTO PET STORE

echo 'Successfully ran the pet store cron!';
