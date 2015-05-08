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

$getalok = mysql_query("SELECT id FROM players WHERE accountlock = '0' ORDER BY id ASC");
while ($aclok = mysql_fetch_array($getalok))
{


	$date = date("d M, y - H:i:s");

// GET ALL OUTSTANDING JOB APPS AND DECIDE
// ALL JOB APPS ARE DELETED EACH NIGHT

	$gja = mysql_query("SELECT * FROM jobapps WHERE playerid = '$aclok[id]'");
	while ($jap = mysql_fetch_array($gja))
	{

		$decision = mt_rand(1, 50);

		if ($decision > 25)
		{

			$MsgTitle = "Job Application Decision!";
			$Msg = "You recently applied for the position of " . $jap['position'] . ". Great news, you got the job! Be sure to attend the corresponding building to your job title on a daily basis to maintain and build your reputation, you start today.";

// WHILE AND INSERT APPROVED JOBAPPS INTO JOBS
// INSERT INTO THE JOBS

			if ($jap['position'] == 'Bank Cashier')
			{
				$url = "/bank.php";
			}
			if ($jap['position'] == 'Vet Assistant')
			{
				$url = "/vet.php";
			}
			if ($jap['position'] == 'Superstore Assistant')
			{
				$url = "/superstore.php";
			}
			if ($jap['position'] == 'Shelter Helper')
			{
				$url = "/shelter.php";
			}
			if ($jap['position'] == 'Training Assistant')
			{
				$url = "/train.php";
			}
			if ($jap['position'] == 'Event Assitant')
			{
				$url = "/searchevents.php";
			}

			$new = "3";

			$newjob = mysql_query("INSERT INTO jobs(playerid, position, salary, level, started, url, new) VALUES ('$jap[playerid]','$jap[position]','1500', 'Beginner', '$date', '$url', '$new')");

/// WHEEEEEE
		} else
		{

			$MsgTitle = "Job Application Decision!";
			$Msg = "You recently applied for the position of " . $jap['position'] . ". Unfortunately, your application was unsuccessful. However, don&#039;t be dis-heartened! You can always apply for another...";
		}


// ALERT MEMBER OF DECISION
		Fez\InsertAlert($DB_Con, $jap['playerid'], $Msg, $MsgTitle);
	}



// DEDUCT ONE FROM NEW STATUS

	$minusl = mysql_query("UPDATE jobs SET new = new-1 WHERE new > 0 AND playerid = '$aclok[id]'");


// NOW DELETE ALL JOB APPLICATIONS

	$del = mysql_query("DELETE FROM jobapps WHERE id > 0  AND playerid = '$aclok[id]'");
}

echo 'Successfully ran the daily job cron!';
echo 'Script completed in ' . $t1->stop_show_timer() . ' secs.';
