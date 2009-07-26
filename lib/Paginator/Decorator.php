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
 * All decorators must extend and implement that class.
 * 
 * @author Jacek "eXtreme" Jędrzejewski
 */
abstract class Opc_Paginator_Decorator
{
	/**
	 * Paginator instance
	 * @var Opc_Paginator_Range
	 */
	protected $_paginator = null;
	
	/**
	 * Setup is executed when the pager is in the dirty state.
	 * Use for init calculations.
	 * @return void
	 */
	public function setup()
	{ 
	} // end setup();

	/**
	 * Imports the decorator options from an array.
	 *
	 * @param array $config The option list
	 */
	final public function loadConfig(array $config)
	{
		foreach($config as $name => $value)
		{
			$this->set($name, $value);
		}
	} // end loadConfig();
	
	/**
	 * Magic function so that $obj->key = "value" will work
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return true
	 */
	final public function __set($key, $value)
	{ 
		return $this->set($key, $value);
	} // end __set();
	
	/**
	 * @param string $key
	 * @param mixed $value
	 * @return true
	 */
	public function set($key, $value)
	{ 
		$key = trim($key, '_');
		
		if(isset($this->$key))
		{
			$this->_paginator->state(Opc_Paginator_Range::STATE_DIRTY);
			$this->$key = $value;
			return true;
		}
		
		throw new Opc_OptionNotExists_Exception($key, get_class($this));
	} // end set();
		
	/**
	 * Magic function so that $obj->key will work
	 *
	 * @param string $key
	 * @return mixed
	 */
	final public function __get($key)
	{
		return $this->get($key);
	} // end __get();
	
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{ 
		$key = trim($key, '_');
		
		if(isset($this->$key))
		{
			return $this->$key;
		}
		
		throw new Opc_OptionNotExists_Exception($key, get_class($this));
	} // end get();
	
	/**
	 * @param Opc_Paginator_Range $paginator Current paginator instance
	 * @return void
	 */
	final public function setPaginator(Opc_Paginator_Range $paginator)
	{
		$this->_paginator = $paginator; 
	} // end setPaginator();
	
	/**
	 * This method should return an array. Used in Iterator interface
	 * of the paginator.
	 * 
	 * @return array
	 */
	abstract public function current();
} // end Opc_Paginator_Decorator;
