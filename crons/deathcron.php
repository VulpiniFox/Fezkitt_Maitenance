<?php

if (isset($_SERVER['HTTP_HOST']))
{
	exit('No joy.');
}

ini_set('display_errors', 'On');
require_once 'settings.php';
require_once Fez\INC_ROOT . 'configure.php';
$t1 = new Fez\code_timer;

$dbhost = 'swgdb.cgnwvdsnszvj.us-east-1.rds.amazonaws.com';
$dbuser = 'fez_user';
$dbpass = 'T8rmQ6c!P$byGWA';
$dbname = 'fezgamedb';


$link = @mysql_pconnect($dbhost, $dbuser, $dbpass) or die("A link couldn't be made to the MySQL database server. Please try again. If the problem persists, please contact the server administrator.");
$selectdb = @mysql_select_db($dbname, $link) or die("The MySQL database couldn't be selected. Please try again. If the problem persists, please contact the server administrator.");


// END DB CONNECT
// GET ALL FERRETS AND GIVE THEM A SECOND CHANCE

$selectd = mysql_query("SELECT id, name, owner FROM ferrets WHERE age > 336");
while ($death = mysql_fetch_array($selectd))
{
	$chance = mt_rand(1, 24);

	if ($chance == 1)
	{
// ALERT THE MEMBER

		$MsgTitle = "R.I.P - " . $death['name'] . ".";
		$Msg = $death['name'] . " has passed away.";

		\Fez\InsertAlert($DB_Con, $death['owner'], $Msg, $MsgTitle);

		$deleterip = mysql_query("UPDATE ferrets SET owner = -1 WHERE id = " . $death['id'] . " LIMIT 1");
		$unattachitems = mysql_query("UPDATE items SET assignedto = 0 WHERE assignedto = " . $death['id']);
	}
}

echo 'Completed Death Cron...';
