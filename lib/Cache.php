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
 * The class represents a caching system for Open Power Template 2.
 */
class Opc_Cache implements Opt_Caching_Interface
{
	/**
	 * The view that the caching system is connected to.
	 * @var Opt_View
	 */
	private $_view;

	/**
	 * The Opc_Class object.
	 * @var Opc_Class
	 */
	private $_opc;

	/**
	 * The caching extra key.
	 * @var String
	 */
	private $_key = null;

	/**
	 * The caching validity time.
	 * @var Integer
	 */
	private $_time = 3600;

	/**
	 * Creates a new caching object and connects to it.
	 * 
	 * @param Opt_View $view The view 
	 */
	public function __construct(Opt_View $view)
	{
		$this->_opc = Opl_Registry::get('opc');
		$this->_view = $view;
	} // end __construct();
	
	/**
	 * Sets the extra key for the cache.
	 * @param String|Null $key The extra key
	 */
	public function setKey($key)
	{
		$this->_key = $key;
	} // end setKey();
	
	/**
	 * Sets the expiry time for the specified caching object.
	 * 
	 * @param Integer $time The new expiry time. 
	 */
	public function setExpiryTime($time)
	{
		if(is_integer($time))
		{
			$this->_time = $time;
		}
		else
		{
			throw new Opc_InvalidArgumentType_Exception(gettype($time), 'integer');
		}
	} // end setExpiryTime();
	
	/**
	 * Returns the current extra caching key.
	 * @return String|Null
	 */
	public function getKey()
	{
		return $this->_key;
	} // end getKey();
	
	/**
	 * Returns the current expiry time for the cache.
	 * @return Integer
	 */
	public function getExpiryTime()
	{
		return $this->_time;
	} // end getExpiryTime();

	/**
	 * Checks if the specified view is cached.
	 *
	 * @return boolean
	 */
	public function isCached()
	{

	} // end isCached();

	/**
	 * Clears the cache for the specified view.
	 */
	public function clear()
	{

	} // end clear();

	/**
	 * The interface method of Opt_Caching_Interface. Decides
	 * whether the specified view must be cached.
	 *
	 * @param Opt_View $view The cached view.
	 */
	public function templateCacheStart(Opt_View $view)
	{

	} // end templateCacheStart();

	/**
	 * The interface method of Opt_Caching_Interface. Finalizes
	 * the caching of the view.
	 *
	 * @param Opt_View $view The cached view.
	 */
	public function templateCacheStop(Opt_View $view)
	{
		
	} // end templateCacheStop();
} // end Opc_Cache;