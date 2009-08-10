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
 * Range slider decorator class. It always displays a range of defined number
 * of pages.
 * 
 * 11 12 13 14 [15] 16 17 18 19
 * 
 * @author Jacek "eXtreme" Jędrzejewski
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Opc_Paginator_Decorator_RangeSlider extends Opc_Paginator_Decorator
{
	/**
	 * Number of pages in range. It should be an odd number.
	 * @access public
	 * @var integer
	 */
	protected $range = 9;
	
	/**
	 * Start number before current.
	 * @access private
	 * @var integer
	 */
	protected $_back;
	/**
	 * End number after current
	 * @access private
	 * @var integer
	 */
	protected $_forward;
	
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
			case 'range':
				if($value < 1)
				{
					$value = 1;
				}
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
		if($this->range > $this->_paginator->pageCount)
		{
			$this->range = $this->_paginator->pageCount;
			$this->_back = 1;
			$this->_forward = $this->_paginator->pageCount;
			return true;
		}
		
		$halfl = floor(($this->range-1) / 2);
		$halfr = ceil(($this->range-1) / 2);
		
		$this->_back     = $this->_paginator->page_float - $halfl;
		$this->_forward  = $this->_paginator->page_float + $halfr;
		
		if($this->_paginator->page_float <= $halfl)
		{
			$this->_forward = $this->range-$this->_paginator->page_float+($this->_paginator->page_float-$halfl)+$halfl;
			$this->_back = 1;
		}
		if($this->_paginator->page_float > $this->_paginator->pageCount-$halfr)
		{
			$this->_back -= $this->_paginator->page_float+$halfr-$this->_paginator->pageCount;
			$this->_forward = $this->_paginator->pageCount;
		}
	} // end setup();
	
	/**
	 * Returns current, and "range" numbers.
	 * 
	 * @return array
	 */
	public function current()
	{
		$current = false;
		$i = $this->_paginator->key();
		
		if($i >= $this->_back && $i <= $this->_forward){
			$current = array(
				'item' => 'number',
				'number' => $i,
			);
			
			if($i == $this->_paginator->page_float)
			{
				$current['item'] = 'current';
			}
		}
		
		return $current;
	} // end current();
} // end Opc_Paginator_Decorator_DottingSlider;
