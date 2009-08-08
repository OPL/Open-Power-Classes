<?php
/*
 *  OPEN POWER LIBS <http://www.invenzzia.org>
 *  ==========================================
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 *
 * $Id$
 */

/**
 * The class manages the configuration and plugin loading utilities for OPC.
 * Furthermore, it serves as a factory object for other classes.
 * 
 * @author Tomasz "Zyx" Jędrzejewski
 * @author Jacek "eXtreme" Jędrzejewski
 * @license http://www.invenzzia.org/license/new-bsd New BSD License 
 */
class Opc_Class extends Opl_Class
{
	// Opc_Cache configuration
	/**
	 * Default cache directory.
	 * @var string
	 */
	public $cacheDir = '';
	/**
	 * Cache expiry time.
	 * @var integer
	 */
	public $expiryTime = 3600;
	
	// Opc_Paginator configuration
	/**
	 * Default value for Opc_Paginator_Range->limit
	 * @var integer
	 */
	public $itemsPerPage = 10;
	/**
	 * Default decorator
	 * @var string|Opc_Paginator_Decorator|null
	 */
	public $paginatorDecorator = null;
	/**
	 * Default decorator's options
	 * @var array|null
	 */
	public $paginatorDecoratorOptions = null;

	// Opc_Visit configuration

	/**
	 * The class constructor - registers the main object in the
	 * OPL registry.
	 */
	public function __construct()
	{
		if(Opl_Registry::exists('opc'))
		{
			throw new Opc_CannotCreateAnotherInstance_Exception;
		}
		Opl_Registry::register('opc', $this);
	} // end __construct();

	/**
	 * Constructs and pre-configures the caching object
	 * using the default settings for the specified view.
	 *
	 * @param Opt_View $view The OPT view.
	 * @return Opc_Cache
	 */
	public function cacheFactory(Opt_View $view)
	{
		$cache = new Opc_View_Cache($view);
		$cache->setExpiryTime($this->expiryTime);

		return $cache;
	} // end cacheFactory();

	/**
	 * The plugin loader for OPC.
	 *
	 * @internal
	 * @param string $directory The plugin location
	 * @param SplFileInfo $file The plugin file information
	 * @return string
	 */
	protected function _pluginLoader($directory, SplFileInfo $file)
	{
		return '';
	} // end _pluginLoader();    
} // end Opc_Class;
