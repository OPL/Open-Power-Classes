<?php
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
	Opl_Loader::setHandleUnknownLibraries(false);
	
	Opc_DoctrineModels::setPath(dirname(__FILE__).'/models/');
	Opc_DoctrineModels::setGeneratedModelsDirectoryName('base');
	Opc_DoctrineModels::registerAutoload();
	
	$class = new Model;
}
catch(Opc_Exception $exception)
{
	$handler = new Opc_ErrorHandler;
	$handler->display($exception);
} 
