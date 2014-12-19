<?php

#  Autoloaders.php
#  by Nicole Ward
#  <http://snowwolfegames.com>
#
#  Copyright (c) 2014 - SnowWolfe Games, LLC
#  this script contains our autoload functions

#

namespace Fez;

function ClassLoader($ClassName)
{
	# use with spl_autoload_register()

	$Parts = \explode("\\", $ClassName);
	$File = CLASS_ROOT . \end($Parts) . '.class.php';
	if (!\is_readable($File))
	{
		debug_print_backtrace();
		return false;
	}
	require_once $File;

	# end ClassLoader
}

