<?php

# Logger.class.php
# by Nicole Ward
# <http://snowwolfegames.com>
# <nikki@snowwolfegames.com>
#
# Copyright © 2013 - 2014 - SnowWolfe Games, LLC
# This script is a logging system.
# properties:
# $FileName
# - protected
# - string
# - holds the name of the file to be logged to
# $FileHandle
# - protected
# - resource
# - holds the handle to the file
# $Priorities
# - protected
# - array
# - holds an array of possible priorities
# $Priority
# - protected
# - string
# - holds the priority level for this log message
# $Types
# - protected
# - array
# - holds an array of log types
# $LogTyple
# - protected
# - string
# - holds the type of log this message is
# $SendMail
# - protected
# - boolean
# - holds the flag to send mail or not
# $EmailAddy
# - protected
# - boolean
# - holds the email address to send notices to
# $DataToLog
# - protected
# - string
# - holds the data being logged to the file
#
# methods:
# __construct()
# __clone()
# __destruct()
# init()
# - initializes the logger class by setting variables
# SetFileName()
# - setter for file name
# -- parameters:
# -- $FileName
# 		- string
# 		- holds the name of the desired file to log to
# -- $IncludeDate
# 		- boolean
# 		- flag to include date in file name or not
# SetPriority()
# - setter for priority
# -- parameters:
# -- $Priority
# 		- string
# 		- holds the priority
# SetLogType()
# - setter for log type
# -- parameters:
# -- $LogType
# 		- string
# 		- holds the log type
# SetSendMail()
# - setter for send mail
# -- parameters:
# -- $SendMail
# 		- string
# 		- holds the value for set mail
# SetEmailAddy()
# - setter for email for send mail
# -- parameters:
# -- $EmailAddy
# 		- string
# 		- holds the value for email address to send to
# OpenLogFile()
# - opens the log file
# CloseLogFile()
# - closes the log file
# ChModMyFile()
# - changes the permissions for a file
# WriteToLog()
# - writes the log data to the file
# -- parameters:
# -- $LogData
# 		- string
# 		- holds the data to be logged
# -- $LogDetails
# 		- boolean
# 		- flags whether or not to log the details such as date and time to the file with the message
# 		- defaults to \TRUE

namespace Fez;

class Logger
{

	protected $FilePath = \NULL;
	protected $FileName;
	protected $FileHandle;
	protected $Priorities = array(
		"Critical",
		"High",
		"Medium",
		"Low",
		"Trivial");
	protected $Priority = 'Medium';
	protected $Types = array(
		"Error",
		"Warning",
		"Notice",
		"Info",
		"Debugging");
	protected $LogType = 'Error';
	protected $SendMail = \FALSE;
	protected $EmailAddy = \NULL;
	protected $DataToLog;

	public function __construct()
	{

	}


# end __construct()

	public function __clone()
	{
		\trigger_error('Clone is not allowed.', E_USER_ERROR);
	}


# end __clone()

	public function __destruct()
	{
		$this->CloseLogFile();
	}


# end __destruct()

	public function init($FileName, $IncludeDate = \TRUE, $Priority = 'Medium', $LogType = 'Error', $SendMail = \FALSE)
	{
		$this->SetFileName($FileName, $IncludeDate);
		$this->SetPriority($Priority);
		$this->SetLogType($LogType);
		$this->SetSendMail($SendMail);

		return $this;
	}


# end init()

	public function SetFilePath($Path)
	{
		$this->FilePath = $Path;

		return $this;
	}


# end SetFilePath()

	public function SetFileName($FileName, $IncludeDate = \TRUE)
	{
		$this->FileName = ($IncludeDate == \TRUE)
			? $FileName . '-' . \date("Y-m-d") . '.log'
			: $FileName . '.log';

		return $this;
	}


# end SetFileName()

	public function SetPriority($Priority)
	{
		if (\in_array($Priority, $this->Priorities))
		{
			$this->Priority = $Priority;
		}

		return $this;
	}


# end SetPriority()

	public function SetLogType($LogType = 'error')
	{
		if (in_array($LogType, $this->Types))
		{
			$this->LogType = $LogType;
		}

		return $this;
	}


# end SetLogType()

	public function SetSendMail($SendMail)
	{
		$this->SendMail = $SendMail;

		return $this;
	}


# end SetSendMail()

	public function SetEmailAddy($EmailAddy)
	{
		$this->EmailAddy = $EmailAddy;

		return $this;
	}


# end SetEmailAddy()

	public function OpenLogFile()
	{
		if (empty($this->FileName))
		{
			throw new \Exception("FileName was not set.");
		}

		if (!\is_resource($this->FileHandle))
		{
			$Exists = (\file_exists($this->FilePath . $this->FileName))
				? \TRUE
				: \FALSE;
			$this->FileHandle = \fopen($this->FilePath . $this->FileName, "a");
			if ($this->FileHandle == \FALSE)
			{
				throw new \Exception("Failed to open file.");
			}
			if ($Exists == \FALSE)
			{
				$this->ChModMyFile($this->FilePath . $this->FileName);
			}
		}

		return $this;
	}


# end OpenLogFile()

	public function CloseLogFile()
	{
		if (\is_resource($this->FileHandle))
		{
			\fclose($this->FileHandle);
		}

		return $this;
	}


# end CloseLogFile()

	protected function ChModMyFile()
	{
		if (\function_exists('posix_getuid'))
		{
			if (\substr(\sprintf('%o', \fileperms($this->FilePath . $this->FileName)), -4) != 0766)
			{
				if (\fileowner($this->FilePath . $this->FileName) == \posix_getuid())
				{
					\chmod($this->FilePath . $this->FileName, 0766);
				}
			}
		}
	}


# end ChModMyFile()

	public function WriteToLog($LogData, $LogDetails = \FALSE)
	{
		if (\is_resource($this->FileHandle))
		{
			if ($LogDetails == \TRUE)
			{
				$LogDetails = '';
				$LogDetails .= \str_pad(\date("Y-m-d") . ' ' . \date("H:i:s"), 100);
				$LogDetails .= \str_pad($this->Priority, 100) . \str_pad($this->LogType, 100);
				$Check = \fwrite($this->FileHandle, $LogDetails . \PHP_EOL);
				if ($Check == \FALSE)
				{
					throw new \Exception("Unable to write log details.");
				}
			}

			if (\is_array($LogData))
			{
				$Msg = '';
				foreach ($LogData as $key => $value)
				{
					$Msg .= \PHP_EOL . $key . " - " . \wordwrap($value, 60);
				}
				$Msg .= \PHP_EOL . \PHP_EOL;
			} else
			{
				$Msg = \wordwrap($LogData, 80);
				$Msg .= \PHP_EOL . \PHP_EOL;
			}

			$Check = \fwrite($this->FileHandle, $Msg);
			if ($Check == \FALSE)
			{
				throw new \Exception("Unable to write log message.");
			}
			if ($this->SendMail == \TRUE)
			{
				\mail($this->EmailAddy, "Check " . $this->FilePath . $this->FileName . "!", $this->Priority . ' ' . $this->LogType . ' recorded at ' . \date("Y-m-d") . ' ' . \date("H:i:s"));
			}
		} else
		{
			throw new \Exception("No file open.");
		}

		return $this;
	}


# end WriteToLog()
}

# end Logger class