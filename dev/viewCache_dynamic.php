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
	
	$viewSettings = array(
		'sourceDir' => './templates/',
		'compileDir' => './templates_c/',
		'prologRequired' => true,
		'stripWhitespaces' => false,
		'gzipCompression' => true,
		'contentType' => 0,
		'charset' => 'utf-8'
	);
	$tpl = new Opt_Class;
	$tpl->setup($viewSettings);

	$opc = new Opc_Class;
	$viewCacheOptions = array(
		'cacheDir' => './cache/',
		'expiryTime' => 120,
		'key' => null
	);
	$viewCache = new Opc_View_Cache($viewCacheOptions);
	$tpl->setCache($viewCache);
	$view = new Opt_View('caching_test_dynamic.tpl');
	$view->pagetitle = 'Caching system test';
	$view->dynamic = 'Dynamic3';

	$out = new Opt_Output_Http;
	$out->setContentType();
	$out->render($view);
}
catch(Opc_Exception $exception)
{
	$handler = new Opc_ErrorHandler;
	$handler->display($exception);
} 
