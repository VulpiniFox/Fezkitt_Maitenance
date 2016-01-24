<?php

if (isset($_SERVER['HTTP_HOST']))
{
	exit('No joy.');
}

$dbhost = '10.0.0.6:3306';
$dbuser = 'fez_user';
$dbpass = 'T8rmQ6c!P$byGWA';
$dbname = 'fezgamedb';


$link = @mysql_pconnect($dbhost, $dbuser, $dbpass) or die("A link couldn't be made to the MySQL database server. Please try again. If the problem persists, please contact the server administrator.");
$selectdb = @mysql_select_db($dbname, $link) or die("The MySQL database couldn't be selected. Please try again. If the problem persists, please contact the server administrator.");


// END DB CONNECT
// SET THE UPGRADED ITEM FIELD TO 0 TO ALLOW PLAYERS TO GET ITEMS AGAIN

$updateuic = mysql_query("UPDATE players SET upgradeditem = 0");

echo 'Successfully ran the upgraded item cron!';
?>