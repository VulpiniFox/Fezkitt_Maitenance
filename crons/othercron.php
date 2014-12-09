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

$new = mysql_query("UPDATE jobs SET attended = 0");


$getallunlocked = mysql_query("SELECT * FROM players WHERE accountlock = '0' ORDER BY id ASC");
while ($acc = mysql_fetch_array($getallunlocked))
{


// DAILY WORK ATTENDED
// SHOOTING STAR GAME COUNTER

	$newgame = mysql_query("UPDATE players SET shootingstargame = 0 WHERE id = '$acc[id]'");
}

// END OF LOCKED ACCOUNT STUFF FOR THIS FILE
// ONLINE TODAY COUNTER

$newoc = mysql_query("UPDATE players SET onlinetoday = 0");

// DELETE MESSAGE SCRIPT BELOW
// UPDATE THE MESSAGE CRON + 1 IF IT'S BEEN INACTIVE

$newinactivity = mysql_query("UPDATE messages SET inactivity = inactivity+1 WHERE activetoday = 0");
$newinacttoday = mysql_query("UPDATE messages SET activetoday = 0");

$upgradeddeduct = mysql_query("UPDATE players SET accountexpires = accountexpires-1 WHERE account = 'Upgraded' AND accountexpires > 0");
$upn = mysql_query("UPDATE players SET account = 'Normal' WHERE accountexpires = 0 AND account = 'Upgraded'");
$upcount = mysql_query("UPDATE players SET accountexpires = '' WHERE accountexpires = 0");
$updatetag = mysql_query("UPDATE players SET tag = '' WHERE accounttype = 'Normal'");


$newcave = mysql_query("UPDATE players SET cave = 0");


echo 'Successfully ran the other mischellaneous cron!';
