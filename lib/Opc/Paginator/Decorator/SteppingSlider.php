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
 */

/**
 * Stepping slider decorator class. Extends slider decorator, but instead
 * of gaps it returns steps. 
 *   
 * 1 2 3 <10> <20> 22 23 [24] 25 26 <30> <40> <50> <60> 65 66 67
 *
 * @author Jacek "eXtreme" Jędrzejewski
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Opc_Paginator_Decorator_SteppingSlider extends Opc_Paginator_Decorator_Slider
{
	/**
	 * Number of pages per one step.
	 * @access public
	 * @var integer
	 */
	protected $stepping = 10;
	
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
		
		parent::setup();
		
		$steps = ceil($pageCount / $this->stepping)+1;
		$step = floor($page / $this->stepping);
		
		$this->_steps[] = 1;
		
		while($steps-- >= 0)
		{
			$this->_steps[] = $steps * $this->stepping;
		}
	} // end setup();
	
	/**
	 * Replaces static separator of "Slider" with a step number.
	 * 
	 * @access private
	 * @return array
	 */
	protected function _separator()
	{
		$current = false;
		$i = $this->_paginator->key();
		
		if(in_array($i, $this->_steps))
		{
			$current = array(
				'item' => 'step',
				'number' => $i,
			);
		}
		
		return $current;
	} // end _separator();
} // end Opc_Paginator_Decorator_SteppingSlider;
