<?php
/**
 * The bootstrap file for unit tests.
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @author Amadeusz "megawebmaster" Starzykiewicz
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */

echo "Loading bootstrap...\n";
date_default_timezone_set('Europe/Warsaw');

$config = parse_ini_file(dirname(__FILE__).'/../paths.ini', true);
require($config['libraries']['Opl'].'Opl/Loader.php');

$nsLoader = new Opl_Loader;
// Libraries that use PHP 5.3 namespaces go here.
$nsLoader->addLibrary('Symfony', $config['libraries']['Symfony']);
$nsLoader->addLibrary('Opc', $config['libraries']['Opc']);
$nsLoader->register();

$oplLoader = new Opl_Loader('_');
$oplLoader->addLibrary('Opl', $config['libraries']['Opl']);
$oplLoader->addLibrary('Opt', $config['libraries']['Opt']);
$oplLoader->addLibrary('Extra', './Extra/');
$oplLoader->register();