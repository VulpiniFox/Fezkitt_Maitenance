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

$selecta = mysql_query("SELECT id, name, owner FROM ferrets WHERE age >= 288 AND age < 312");
while ($deatha = mysql_fetch_array($selecta))
{
	$chancea = mt_rand(1, 40);

	if ($chancea == 1)
	{
// ALERT THE MEMBER

		$MsgTitle = "R.I.P - " . $deatha['name'] . ".";
		$Msg = $deatha['name'] . " has passed away.";

		\Fez\InsertAlert($DB_Con, $deatha['owner'], $Msg, $MsgTitle);

		$deleterip = mysql_query("UPDATE ferrets SET owner = -1 AND incage = 0 WHERE id = " . $deatha['id'] . " LIMIT 1");
		$unattachitems = mysql_query("UPDATE items SET assignedto = 0 WHERE assignedto = " . $deatha['id']);
	}
}

$selectb = mysql_query("SELECT id, name, owner FROM ferrets WHERE age >= 312 AND age < 336");
while ($deathb = mysql_fetch_array($selectb))
{
	$chanceb = mt_rand(1, 18);

	if ($chanceb == 1)
	{
// ALERT THE MEMBER

		$MsgTitle = "R.I.P - " . $deathb['name'] . ".";
		$Msg = $deathb['name'] . " has passed away.";

		\Fez\InsertAlert($DB_Con, $deathb['owner'], $Msg, $MsgTitle);

		$deleterip = mysql_query("UPDATE ferrets SET owner = -1 AND incage = 0 WHERE id = " . $deathb['id'] . " LIMIT 1");
		$unattachitems = mysql_query("UPDATE items SET assignedto = 0 WHERE assignedto = " . $deathb['id']);
	}
}

$selectc = mysql_query("SELECT id, name, owner FROM ferrets WHERE age >= 336 AND age < 384 OR health <= -100 AND health > -200");
while ($deathc = mysql_fetch_array($selectc))
{
	$chancec = mt_rand(1, 5);

	if ($chancec == 1)
	{
// ALERT THE MEMBER

		$MsgTitle = "R.I.P - " . $deathc['name'] . ".";
		$Msg = $deathc['name'] . " has passed away.";

		\Fez\InsertAlert($DB_Con, $deathc['owner'], $Msg, $MsgTitle);

		$deleterip = mysql_query("UPDATE ferrets SET owner = -1 AND incage = 0 WHERE id = " . $deathc['id'] . " LIMIT 1");
		$unattachitems = mysql_query("UPDATE items SET assignedto = 0 WHERE assignedto = " . $deathc['id']);
	}
}

$selectd = mysql_query("SELECT id, name, owner FROM ferrets WHERE age >= 384 AND age < 432 OR health <= -200 AND health > -300");
while ($deathd = mysql_fetch_array($selectd))
{
	$chanced = mt_rand(1, 3);

	if ($chanced == 1)
	{
// ALERT THE MEMBER

		$MsgTitle = "R.I.P - " . $deathd['name'] . ".";
		$Msg = $deathd['name'] . " has passed away.";

		\Fez\InsertAlert($DB_Con, $deathd['owner'], $Msg, $MsgTitle);

		$deleterip = mysql_query("UPDATE ferrets SET owner = -1 AND incage = 0 WHERE id = " . $deathd['id'] . " LIMIT 1");
		$unattachitems = mysql_query("UPDATE items SET assignedto = 0 WHERE assignedto = " . $deathd['id']);
	}
}

$selecte = mysql_query("SELECT id, name, owner FROM ferrets WHERE age >= 432 AND age < 480 OR health <= -300 AND health > -400");
while ($deathe = mysql_fetch_array($selecte))
{
	$chancee = mt_rand(1, 5);

	if ($chancee < 4)
	{
// ALERT THE MEMBER

		$MsgTitle = "R.I.P - " . $deathe['name'] . ".";
		$Msg = $deathe['name'] . " has passed away.";

		\Fez\InsertAlert($DB_Con, $deathe['owner'], $Msg, $MsgTitle);

		$deleterip = mysql_query("UPDATE ferrets SET owner = -1 AND incage = 0 WHERE id = " . $deathe['id'] . " LIMIT 1");
		$unattachitems = mysql_query("UPDATE items SET assignedto = 0 WHERE assignedto = " . $deathe['id']);
	}
}

$selectf = mysql_query("SELECT id, name, owner FROM ferrets WHERE age >= 480 AND age < 528 OR health <= -400 AND health > -500");
while ($deathf = mysql_fetch_array($selectf))
{
	$chancef = mt_rand(1, 4);

	if ($chancef < 4)
	{
// ALERT THE MEMBER

		$MsgTitle = "R.I.P - " . $deathf['name'] . ".";
		$Msg = $deathf['name'] . " has passed away.";

		\Fez\InsertAlert($DB_Con, $deathf['owner'], $Msg, $MsgTitle);

		$deleterip = mysql_query("UPDATE ferrets SET owner = -1 AND incage = 0 WHERE id = " . $deathf['id'] . " LIMIT 1");
		$unattachitems = mysql_query("UPDATE items SET assignedto = 0 WHERE assignedto = " . $deathf['id']);
	}
}

$selectg = mysql_query("SELECT id, name, owner FROM ferrets WHERE age >= 528 OR health <= -500");
while ($deathg = mysql_fetch_array($selectg))
{
	$chanceg = mt_rand(1, 5);

	if ($chanceg < 5)
	{
// ALERT THE MEMBER

		$MsgTitle = "R.I.P - " . $deathg['name'] . ".";
		$Msg = $deathg['name'] . " has passed away.";

		\Fez\InsertAlert($DB_Con, $deathg['owner'], $Msg, $MsgTitle);

		$deleterip = mysql_query("UPDATE ferrets SET owner = -1 AND incage = 0 WHERE id = " . $deathg['id'] . " LIMIT 1");
		$unattachitems = mysql_query("UPDATE items SET assignedto = 0 WHERE assignedto = " . $deathg['id']);
	}
}
echo 'Completed Death Cron...';
