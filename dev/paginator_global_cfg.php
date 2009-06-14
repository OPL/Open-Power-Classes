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
	$opc = new Opc_Class;
	$opc->itemsPerPage = 13;
	$opc->paginationDecorator = 'jumper';
//	$opc->paginationDecoratorOptions;

	$pager = Opc_Paginator::create(1000); // returns Opc_Paginator_Pager;
	$pager->all = 1000;
	$pager->page = isset($_GET['page']) ? $_GET['page'] : 1;

	echo '<p>';
	foreach($pager as $page)
	{
		switch($page['item'])
		{
			case 'current': echo ' <strong>['.$page['number'].']</strong> '; break;
			case 'number': echo ' <a href="?page='.$page['number'].'">'.$page['number'].'</a> '; break;
			case 'step': echo ' <a href="?page='.$page['number'].'"><small>'.$page['number'].'</small></a> '; break;

		}
	}
	echo '</p>';

}
catch(Opc_Exception $exception)
{
	$handler = new Opc_ErrorHandler;
	$handler->display($exception);
}
