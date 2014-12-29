<?php

# basic maintenance script settings.
ini_set('max_execution_time', 0);

define('Fez\SRVR_ROOT', '/var/www/');
define('Fez\HTML_ROOT', Fez\SRVR_ROOT.'html/');
define('Fez\DOC_ROOT', Fez\HTML_ROOT.'Maintenance/');
define('Fez\MAINT_ROOT', Fez\DOC_ROOT . 'crons/');
define('Fez\FUNC_ROOT', Fez\DOC_ROOT . 'FezFunctions/');
define('Fez\CLASS_ROOT', Fez\DOC_ROOT . 'FezClasses/');
define('Fez\INC_ROOT', Fez\DOC_ROOT . 'FezIncReq/');
define('Fez\MYBB_ROOT', Fez\DOC_ROOT . 'mybb/');
define('Fez\ERROR_LOG', Fez\SRVR_ROOT . 'Fez_Error-Logs/');
define('Fez\OTHER_LOG', Fez\SRVR_ROOT . 'Fez_Other-Logs/');
define('Fez\IMG_LAYERS', Fez\DOC_ROOT . 'FezLayers/');
define('Fez\GAMELIB_ROOT', Fez\DOC_ROOT . 'lib/');
define('Fez\LIB_ROOT', Fez\HTML_ROOT.'Lib/');
define('Fez\AUTOMATED', TRUE);

# for debugging in case our server globals change again
//echo '<pre>';
//print_r($_SERVER);
//echo '</pre>';
?>