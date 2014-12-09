<?php

if (!isset($_SESSION['id']))
{
	$loggedin = 0; // may be used in original code. remove after rewrite

	if (defined('RESTRICTED'))
	{
		$UserMsg = 'You must be logged in to view that page.';
		Fez\LocationHeader($UserMsg, 'index.php');
	}
} else
{
	$loggedin = 1; // may be used in original code. remove after rewrite

	/*	 * ********
	 * remove this section after these variables are all replaced in site code
	 * replace with user object
	 * **** */

	$Columns = [
		[
			'Field' => '*'
		]
	];
	$Where = [
		[
			'FirstOperand'		 => 'id',
			'ExpressionOperator' => '=',
			'SecondOperand'		 => $_SESSION['id']
		]
	];

	try {
		$DB_Con->SetTable('players')
			->SetColumns($Columns)
			->SetWhere($Where)
			->SetLimit(1);
		$Result = $DB_Con->Query('SELECT', 'Standard');
		$user = $DB_Con->FetchResults($Result, "assoc");
		$DB_Con->CloseResult($Result);
	} catch (\InvalidArgumentException $e) {
		\ignore_user_abort(\TRUE);
		$UserMsg = 'There was an error getting your user record. The error has been logged. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error, $UserMsg, 'error.php');
	} catch (\NoResultException $e) {
		\ignore_user_abort(\TRUE);
		$UserMsg = 'There was an error getting your user record. The error has been logged. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error, $UserMsg, 'error.php');
	} catch (\DBException $e) {
		\ignore_user_abort(\TRUE);
		$UserMsg = 'There was an error getting your user record. The error has been logged. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error, $UserMsg, 'error.php');
	} catch (\Exception $e) {
		\ignore_user_abort(\TRUE);
		$UserMsg = 'There was an error getting your user record. The error has been logged. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
		$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
		Fez\HandleError($error, $UserMsg, 'error.php');
	} finally {
		$DB_Con->ResetQuery();
	}


	$playerID = $user['id'];
	$alias = $user['alias'];
	$name = $user['alias'];
	$UserPass = $user['password'];
	$verified = $user['verified'];
	$vcode = $user['verificationcode'];
	$FD = $user['FD'];
	$FP = $user['FP'];
	$XP = $user['XP'];
	$Level = \Fez\getPlayerLevel($XP);
	$trailor = $user['trailor'];
	$region = $user['region'];
	$tag = $user['tag'];
	$onhand = $user['onhand'];
	$accounttype = $user['account'];
	$randomevent = $user['randomevent'];
	$banned = $user['BANNED'];
	$autocaref = $user['autocarefeature'];
	$upgradeditem = $user['upgradeditem'];
	$accountlock = $user['accountlock'];
	$admin = $user['administrator'];
	if (!empty($admin))
	{
		$_SESSION['USER_ADMIN'] = 1;
	} else
	{
		$_SESSION['USER_ADMIN'] = FALSE;
	}
	$moderator = $user['moderator'];
	$caveact = $user['caveactivity'];
	$cave = $user['cave'];
	$eventcredits = $user['eventcredits'];
	$prefixl = $user['prefixlicense'];
	$prefix = $user['prefix'];
	/*	 * ******** */
}
