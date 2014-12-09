<?php

if (isset($_SERVER['HTTP_HOST']))
{
	exit('No joy.');
}

$dbhost = 'swgdb.cgnwvdsnszvj.us-east-1.rds.amazonaws.com';
$dbuser = 'fez_user';
$dbpass = 'T8rmQ6c!P$byGWA';
$dbname = 'fezgamedb';


$link = @mysql_pconnect($dbhost, $dbuser, $dbpass) or die("A link couldn't be made to the MySQL database server. Please try again. If the problem persists, please contact the server administrator.");
$selectdb = @mysql_select_db($dbname, $link) or die("The MySQL database couldn't be selected. Please try again. If the problem persists, please contact the server administrator.");


// END DB CONNECT

$seltm = mysql_query("SELECT id FROM players WHERE accountlock = 0 ORDER BY id ASC");
while ($utc = mysql_fetch_array($seltm))
{


	$selfer = mysql_query("SELECT id FROM ferrets WHERE owner = '$utc[id]' ORDER BY id ASC");
	while ($fert = mysql_fetch_array($selfer))
	{


// UPDATE COUNTER FOR TRAINING

		$newc = mysql_query("UPDATE train SET recent = '0' WHERE ferretid = '$fert[id]'");
	}
}





echo 'Successfully ran the train cron!';
?>