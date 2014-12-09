<?php

# configure.php
# by Nicole Ward
# <http://snowwolfegames.com>
#
# Copyright (c) 2008 - 2014 - SnowWolfe Games, LLC
#
# this script contains basic configurations for the site pages/scripts

ini_set('display_errors', 'Off');
error_reporting(E_ALL);
date_default_timezone_set('America/New_York');
//ini_set('arg_separator.output','&amp;');

if (!empty($_SERVER['DOCUMENT_ROOT']))
{
	# start a session if we aren't using the shell or cron
	//ini_set('session.cookie_domain', COOKIE_URL);
	\session_name('fezgamesess');
	if (!isset($_SESSION))
	{
		\session_start();
		\setcookie(\session_name(), \session_id(), \time() + 3600, "/");
	}

	\header("Cache-control: private, must-revalidate");
	\header("Content-Type: text/html; charset=utf-8");
}


require_once Fez\LIB_ROOT.'aws-autoloader.php';
# register the autoload function because I am sick
# of forgetting to do include files for classes
# can have multiple loaders registered

require_once Fez\FUNC_ROOT . 'AutoloaderFuncs.php';
try {
	spl_autoload_register('Fez\ClassLoader');
} catch (Exception $e) {
	\ignore_user_abort(\TRUE);
	$UserMsg = 'There was an error checking the game&#039;s status. It has been logged and reported to the Administration. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
	$error = __FILE__ . " " . __LINE__ . \PHP_EOL . $e->getMessage() . \PHP_EOL . $e->getTraceAsString();
	\error_log($error, 3, Fez\ERROR_LOG . 'error-' . date("Y-m-d") . '.log');
}

//require_once Fez\INC_ROOT . 'configMyBBIntegration.inc.php';
require_once Fez\INC_ROOT . 'ets.php';
require_once Fez\FUNC_ROOT . 'UtilityFuncs.php';
require_once Fez\INC_ROOT . 'DBConnect.inc.php';
\set_error_handler('Fez\MyErrorHandler');

try {
	Fez\SetGameConstants($DB_Con);
} catch (\NoResultException $e) {
	\ignore_user_abort(\TRUE);
	$UserMsg = 'There was an error checking the game&#039;s status. It has been logged and reported to the Administration. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error, $UserMsg, 'error.php');
} catch (\InvalidArgumentException $e) {
	\ignore_user_abort(\TRUE);
	$UserMsg = 'There was an error checking the game&#039;s status. It has been logged and reported to the Administration. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error, $UserMsg, 'error.php');
} catch (\DBException $e) {
	\ignore_user_abort(\TRUE);
	$UserMsg = 'There was an error checking the game&#039;s status. It has been logged and reported to the Administration. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error, $UserMsg, 'error.php');
} catch (\Exception $e) {
	\ignore_user_abort(\TRUE);
	$UserMsg = 'There was an error checking the game&#039;s status. It has been logged and reported to the Administration. Please try again later. If this problem persists, please submit a bug ticket. <a href="' . Fez\BUG_TRACKER . '">SnowWolfe Games Bug Tracker</a>';
	$error = __FILE__ . " " . __LINE__ . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString();
	Fez\HandleError($error, $UserMsg, 'error.php');
}
