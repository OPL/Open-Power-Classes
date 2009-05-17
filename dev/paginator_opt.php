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
	$tpl = new Opt_Class;
   	$tpl->sourceDir = './templates/';
   	$tpl->compileDir = './templates_c/';
   	$tpl->charset = 'utf-8';
   	$tpl->compileMode = Opt_Class::CM_REBUILD;
   	$tpl->stripWhitespaces = false;
   	$tpl->setup();

	$pager = Opc_Paginator::create(1000, 13); // returns Opc_Paginator_Pager;
	$pager->all = 1000;
	$pager->page = isset($_GET['page']) ? $_GET['page'] : 1;
	
	
	$view = new Opt_View('paginator_opt.tpl');
	$view->pager = $pager;
	$view->setFormat('pager', 'Objective/Array'); 

		
	$out = new Opt_Output_Http;
	$out->setContentType(Opt_Output_Http::HTML);
	$out->render($view); 
	
}
catch(Opc_Exception $exception)
{
	$handler = new Opc_ErrorHandler;
	$handler->display($exception);
} 
catch(Opt_Exception $exception)
{
	$handler = new Opt_ErrorHandler;
	$handler->display($exception);
} 
