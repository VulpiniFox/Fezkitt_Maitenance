<?php

# DBException.class.php
# by Nicole Ward
# <http://snowwolfegames.com>
# <nikki@snowwolfegames.com>
#
# Copyright © 2013 - 2014 - SnowWolfe Games, LLC
# This script extends php Exception class.
# properties:
# $Backtrace
# - public
# - array
# - holds the debug_backtrace output.
#
# methods:
# __construct
# -- calls:
# -- parent::__construct()

class DBException extends Exception
{

	public $Backtrace;

	public function __construct($message = false, $code = false, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}


}
