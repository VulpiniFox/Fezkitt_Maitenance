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


$getallun = mysql_query("SELECT * FROM players WHERE accountlock = 0 ORDER BY id ASC");
while ($accun = mysql_fetch_array($getallun))
{


// FIRSTLY GIVE EVERYONE A WEEKY INTEREST RATE ON THEIR BANK ACCOUNT BALANCES!
// THIS DOES NOT RELATE TO WORKING

	$datei = date("d M, y - H:i:s");

	$selecti = mysql_query("SELECT FD, account, id FROM players WHERE id = '$accun[id]'");
	while ($fdint = mysql_fetch_array($selecti))
	{

		if ($fdint['account'] == 'Normal')
		{
			$newbal = ceil($fdint['FD'] * 1.011);
			$rate = "1.1";
		} else
		{
			$newbal = ceil($fdint['FD'] * 1.012);
			$rate = "1.2";
		}

		$MsgTitle = "Bank Interest!";
		$Msg = "You have received a bank interest of $rate%, totaling your new bank balance to $$newbal!";
		Fez\InsertAlert($DB_Con, $fdint['id'], $Msg, $MsgTitle);

		$upin = mysql_query("UPDATE players SET FD = $newbal WHERE id = '$fdint[id]'");


// INSERT THE ACTIONS INTO THE DEB ACTIVITY TABLE

		$Type = "Bank Interest";
		$Msg = "You received bank interest of $rate% making your new FD balanace <font color=green>$$newbal</font> - " . Fez\DATE_TODAY;
		Fez\InsertActivity($DB_Con, $fdint['id'], $Msg, $Type);
	}


// GET ALL OF THE JOBS AND FIRE THOSE WHO WORK LESS THEN 3 DAYS
// WORK OUT PAY AMOUNT AND ALERT MEMBERS OF IT

	$getwor = mysql_query("SELECT * FROM jobs WHERE playerid = '$accun[id]'");
	while ($job = mysql_fetch_array($getwor))
	{


// FIRE THOSE WHO WORK LESS THEN 3 DAYS A WEEK

		if (($job['worked'] < 4) AND ( $job['new'] < 1))
		{

			$date = date("d M, y - H:i:s");

			$MsgTitle = "You're Fired!";
			$Msg = "It appears you missed work too many times this week working a total of " . $job['worked'] . " days out of a possible 7! We&#039;ll have to let you go.";
			Fez\InsertAlert($DB_Con, $job['playerid'], $Msg, $MsgTitle);

// DELETE JOB

			$deljob = mysql_query("DELETE FROM jobs WHERE id = " . $job['id'] . " LIMIT 1");
		} else
		{
			$pay = $job['salary'] * $job['worked'];

			$Msg = "It's Payday!";
			$MsgTitle = "You have worked " . $job['worked'] . " / 7 days this week, you have attained a wage of $" . $pay . "!";
			Fez\InsertAlert($DB_Con, $job['playerid'], $Msg, $MsgTitle);

			$upfd = mysql_query("UPDATE players SET FD = FD + " . $pay . " WHERE id = " . $job[playerid] . " LIMIT 1");

// INSERT THE ACTIONS INTO THE DEB ACTIVITY TABLE

			$Type = "Job Wage";
			$Msg = "You worked " . $job['worked'] . " / 7 days this week! <font color=green>+$" . $pay . "</font> - on " . Fez\DATE_TODAY;
			Fez\InsertActivity($DB_Con, $job['playerid'], $Msg, $Type);


// ADD A COMPLETION AMOUNT BASED ON THE AMOUNT OF DAYS WORKED

			$comp = $job['worked'] / 1.2;
			$completed = ceil($comp);

			$updatecomp = mysql_query("UPDATE jobs SET completed = completed+'$completed' WHERE id = '$job[id]'");
		}
	}



// DETERMINE WHO NEEDS A PROMOTION WITH THE BELOW VALUES
// Beginner - 25%
// Intermediate - 60%
// Expert 100%

	$getrec = mysql_query("SELECT * FROM jobs  WHERE playerid = '$accun[id]'");
	while ($compl = mysql_fetch_array($getrec))
	{


		if (($compl['level'] == 'Beginner') AND ( $compl['completed'] > 24))
		{

			$level = "Intermediate";
			$salary = "2700";

			$newlev = mysql_query("UPDATE jobs SET level = '$level', salary = '$salary' WHERE id = '$compl[id]'");


			$date = date("d M, y - H:i:s");

			$Msg = "Ooo, promotion!";
			$MsgTitle = "It looks like you've really been putting in the hard work with your job! You know what they say, hard work pays off. You have been promoted to level 'Intermediate', and your new salary is $2,700, congratulations!";
			Fez\InsertAlert($DB_Con, $compl['playerid'], $Msg, $MsgTitle);
		}


		if (($compl['level'] == 'Intermediate') AND ( $compl['completed'] > 59))
		{

			$level = "Expert";
			$salary = "5000";

			$newlev = mysql_query("UPDATE jobs SET level = '$level', salary = '$salary' WHERE id = '$compl[id]'");

			$MsgTitle = "Ooo, promotion!";
			$Msg = "It looks like you've really been putting in the hard work with your job! You know what they say, hard work pays off. You have been promoted to level 'Expert', and your new salary is $5,000, congratulations!";
			Fez\InsertAlert($DB_Con, $compl['playerid'], $Msg, $MsgTitle);
		}
	}


// END OF ALERTING PROMITIONS
// FINALLY, UPDATE WORK VALUES TO 0 TO START AGAIN
// END OF ACCOUNT LOCK WHILE LOOP
}


$update1 = mysql_query("UPDATE jobs SET worked = 0");


echo 'Successfully ran the weekly work cron!';
echo 'Script completed in ' . $t1->stop_show_timer() . ' secs.';
