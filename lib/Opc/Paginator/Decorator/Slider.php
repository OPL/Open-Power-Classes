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
namespace Opc\Paginator\Decorator;
use Opc\Paginator\Decorator;
/**
 * Slider decorator class. Displays pages around current, pages at the
 * beggining and at the end and a gap between them.  
 *  
 * 1 2 3 ... 22 23 [24] 25 26 ... 65 66 67
 * 
 * @author Jacek "eXtreme" Jędrzejewski
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Slider extends Decorator
{
	/**
	 * Number of pages around current.
	 * For value 2: "5 6 [7] 8 9"
	 * @access public
	 * @var integer
	 */
	protected $around = 2;
	/**
	 * Number of pages at the begin and the end.
	 * @access public
	 * @var integer
	 */
	protected $chunk = 3;
	
	/**
	 * @access private
	 * @var integer
	 */
	protected $_allchunk;
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
			case 'chunk':
			case 'around':
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
		$this->_allchunk = $this->_paginator->pageCount - $this->chunk;
		$this->_back     = $this->_paginator->page_float - $this->around;
		$this->_forward  = $this->_paginator->page_float + $this->around;
	} // end setup();
	
	/**
	 * Returns current, "around", and "chunk" numbers.
	 * 
	 * @return array
	 */
	public function current()
	{
		$current = false;
		$i = $this->_paginator->key();
		
		if($i > $this->_allchunk || $i <= $this->chunk
			|| $i == $this->_paginator->page_float
			|| ($i >= $this->_back && $i <= $this->_forward)
		){
			$current = array(
				'item' => 'number',
				'number' => $i,
			);
			
			if($i == $this->_paginator->page_float)
			{
				$current['item'] = 'current';
			}
		}
		else
		{
			$current = $this->_separator();
		}
		
		return $current;
	} // end current();
	
	/**
	 * Returns a separator-type item, which is not a number.
	 * 
	 * @access protected
	 * @return array
	 */
	protected function _separator()
	{
		if($this->chunk == 0)
		{
			return false;
		}
		
		$i = $this->_paginator->key();
		$current = array('item' => 'gap');
		
		if($i < $this->_back)
		{
			$this->_paginator->seek(ceil($this->_back - 1));
		}
		elseif($i > $this->_forward)
		{
			$this->_paginator->seek($this->_allchunk);
		}
		
		return $current;
	} // end _separator();
} // end Slider;
