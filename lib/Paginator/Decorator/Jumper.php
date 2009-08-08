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
 * Jumper decorator class. Page ranges jump between steps.
 *  
 * <1> <11> 21 22 23 [24] 25 26 27 28 29 30 <31> <41> <51> <61>
 * 
 * @author Jacek "eXtreme" Jędrzejewski
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Opc_Paginator_Decorator_Jumper extends Opc_Paginator_Decorator
{
	/**
	 * Number of pages per one step.
	 * @access public
	 * @var integer
	 */
	protected $stepping = 10;
	/**
	 * Return steps?
	 * @access public
	 * @var boolean
	 */
	protected $steps = true;
	
	/**
	 * @access private
	 * @var integer
	 */
	protected $_begin;
	/**
	 * @access private
	 * @var integer
	 */
	protected $_end;
	/**
	 * @access private
	 * @var array
	 */
	protected $_steps = array();
	
	/**
	 * A function for setting fields.
	 *
	 * @param string $key Object's field name.
	 * @param mixed $value
	 * @return true
	 */
	public function set($key, $value)
	{
		$key = trim($key, '_');
		
		switch($key)
		{
			case 'stepping':
				$value = (int)$value;
				break;
			case 'steps':
				$value = (boolean)$value;
				break;
		}
		
		return parent::set($key, $value);
	} // end set();
	
	/**
	 * Base initation.
	 * 
	 * @return void
	 */
	public function setup()
	{
		$page = $this->_paginator->page;
		$pageCount = $this->_paginator->pageCount;
		
		$steps = ceil($pageCount / $this->stepping);
		$step = floor($page / $this->stepping);
		$modulo = $page % $this->stepping;
		
		if($modulo == 0 && $step > 0)
		{
			$step -= 1;
		}
		
		if($this->steps)
		{
			while($steps-->=0)
			{
				$this->_steps[] = $steps * $this->stepping + 1;
			}
		}
		
		$this->_begin = $step * $this->stepping;
		$this->_end = $this->_begin + $this->stepping;
	} // end setup();
	
	/**
	 * Returns "number", "current" and "step" items.
	 * 
	 * @return array
	 */
	public function current()
	{
		$current = false;
		$i = $this->_paginator->key();
		
		if($i > $this->_begin && $i <= $this->_end)
		{
			$current = array(
				'item' => 'number',
				'number' => $i,
			);
			
			if($i == $this->_paginator->page_float)
			{
				$current['item'] = 'current';
			}
		}
		elseif($this->steps && in_array($i, $this->_steps))
		{
			$current = array(
				'item' => 'step',
				'number' => $i,
			);
		}
		return $current;
	} // end current();
} // end Opc_Paginator_Decorator_Jumper;
