<?php
/**
 * This file tests the Opc_Visit functionality.
 */

// OPL Initialization
$config = parse_ini_file('../paths.ini', true);
require($config['libraries']['Opl'].'Base.php');
Opl_Loader::loadPaths($config);
Opl_Loader::setCheckFileExists(false);
Opl_Loader::register();
Opl_Registry::setState('opl_debug_console', false);
Opl_Registry::setState('opl_extended_errors', true);

try
{
	$opc = new Opc_Class;

	$visit = Opc_Visit::getInstance();

	//echo '<p>Current host: '.$visit->host.'</p>';
	echo '<pre>';
	var_dump($visit->toArray());
	echo '</pre>';
}
catch(Opc_Exception $exception)
{
	$handler = new Opc_ErrorHandler;
	$handler->display($exception);
}
