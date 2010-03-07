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
	$opc = new Opc_Class;
	$tpl = new Opt_Class;
	$tpl->setup($viewSettings);

	$iniOptions = array(
		'directory' => './langs/',
		'fileExistsCheck' => false
	);
	$yamlOptions = array(
		'directory' => './langs/',
		'fileExistsCheck' => true,
		'compileResult' => true,
		'compileResultDirectory' => './cache/'
	);
	$xmlOptions = array(
		'directory' => './langs/',
		'fileExistsCheck' => false,
		'compileResult' => true,
		'compileResultDirectory' => './cache/'
	);

	$adapterIni = new Opc_Translate_Adapter_Ini($iniOptions);
	$adapterYaml = new Opc_Translate_Adapter_Yaml($yamlOptions);
	$adapterXml = new Opc_Translate_Adapter_Xml($xmlOptions);
	$translate = new Opc_Translate($adapterIni);
	$tpl->setTranslationInterface($translate);

	$translate->setLanguage('en');
	$translate->setGroupAdapter('pl', $adapterYaml);
	$translate->setGroupLanguage('pl', 'pl');
	$translate->setGroupAdapter('de', $adapterXml);
	$translate->setGroupLanguage('de', 'de');

	$view = new Opt_View('translate.tpl');
	$view->pagetitle = 'Translation test';

	$out = new Opt_Output_Http;
	$out->setContentType();
	$out->render($view);
}
catch(Opc_Exception $exception)
{
	$handler = new Opc_ErrorHandler;
	$handler->display($exception);
} 
