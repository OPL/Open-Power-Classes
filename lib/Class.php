<?php
/*
 *  OPEN POWER LIBS <http://libs.invenzzia.org>
 *  ===========================================
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) 2008 Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 *
 * $Id$
 */

/**
 * The class manages the configuration and plugin loading utilities for OPC.
 * Furthermore, it serves as a factory object for other classes.
 */
class Opc_Class extends Opl_Class
{
	// Opc_Cache configuration
	public $cacheDir = null;
	public $expiryTime = 3600;
	public $quantum = 1.0;

	// Opc_Pagination configuration

	// Opc_Visit configuration

	/**
	 * The class constructor - registers the main object in the
	 * OPL registry.
	 */
	public function __construct()
	{
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
		$cache = new Opc_Cache($view);
		$cache->setExpiryTime($this->expiryTime);

		return $cache;
	} // end cacheFactory();

	/**
	 * The plugin loader for OPC.
	 *
	 * @internal
	 * @param String $directory The plugin location
	 * @param SplFileInfo $file The plugin file information
	 * @return String
	 */
	protected function _pluginLoader($directory, SplFileInfo $file)
	{
		return '';
	} // end _pluginLoader();
} // end Opc_Class;