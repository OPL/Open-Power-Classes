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
	 * 1 2 3 .................. 22 23 [24] 25 26 ...................................... 65 66 67
	 */	
	class Opc_Paginator_Decorator_DottingSlider extends Opc_Paginator_Decorator_Slider
	{
		/**
		 * Replaces static separator of "Slider" with a dot representing all pages,
		 * which are not "current", "chunk" and "around".		 
		 * 		 		
		 * @access private		
		 * @return array
		 */	
		protected function _separator()
		{
			$i = $this->_paginator->key();
			
			$current = array(
				'type' => 'dot',
				'number' => $i,
			);
			
			if($i == $this->_paginator->page_float)
			{
				$current['type'] = 'current';
			}
			
			return $current;
		} // end _separator();
	} // end Opc_Paginator_Decorator_DottingSlider;
