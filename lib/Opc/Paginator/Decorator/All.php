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
 * Decorator class, which returns all pages.
 * 
 * @author Jacek "eXtreme" Jędrzejewski
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class All extends Decorator
{
	/**
	 * Returns all page numbers.
	 * 
	 * @return array
	 */
	public function current()
	{
		$i = $this->_paginator->key();
		
		$current = array(
			'item' => 'number',
			'number' => $i,
		);
		
		if($i == $this->_paginator->page_float)
		{
			$current['item'] = 'current';
		}
		
		return $current;
	} // end current();
} // end All;
