<?php

# basic maintenance script settings.
ini_set('max_execution_time', 0);

define('DOC_ROOT', '/var/www/html/Fezkitt/');
define('Fez\SRVR_ROOT', '/var/www/');
define('Fez\MAINT_ROOT', Fez\SRVR_ROOT . 'crons/');
define('Fez\FUNC_ROOT', DOC_ROOT . 'FezFunctions/');
define('Fez\CLASS_ROOT', DOC_ROOT . 'FezClasses/');
define('Fez\INC_ROOT', DOC_ROOT . 'FezIncReq/');
define('Fez\MYBB_ROOT', DOC_ROOT . 'mybb/');
define('Fez\ERROR_LOG', Fez\SRVR_ROOT . 'Fez_Error-Logs/');
define('Fez\OTHER_LOG', Fez\SRVR_ROOT . 'Fez_Other-Logs/');
define('Fez\IMG_LAYERS', DOC_ROOT . 'FezLayers/');
define('Fez\LIB_ROOT', DOC_ROOT . 'lib/');
define('Fez\AUTOMATED', TRUE);

# for debugging in case our server globals change again
//echo '<pre>';
//print_r($_SERVER);
//echo '</pre>';
?>