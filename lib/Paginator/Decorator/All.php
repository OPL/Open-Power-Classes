<?php
/*
 *  OPEN POWER LIBS <http://www.invenzzia.org>
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
	 *
	 */	
	class Opc_Paginator_Decorator_All extends Opc_Paginator_Decorator
	{
		/**
		 * Returns all page numbers
		 * 		 		
		 * @return array
		 */	
		public function current()
		{
			$i = $this->_paginator->key();
			
			$current = array(
				'type' => 'number',
				'number' => $i,
			);
			
			if($i == $this->_paginator->page_float)
			{
				$current['type'] = 'current';
			}
			
			return $current;
		} // end current();
	} // end Opc_Paginator_Decorator_All;
