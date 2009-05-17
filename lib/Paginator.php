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
 *
 */	 	
class Opc_Paginator
{
	/**
	 * List of default decorator aliases	
	 * @static		 
	 * @access private		 
	 * @var array
	 */		 		
	protected static $_decorators = array(
		'all'				=> 'Opc_Paginator_Decorator_All',
		'slider'			=> 'Opc_Paginator_Decorator_Slider',
		'dotting_slider'	=> 'Opc_Paginator_Decorator_DottingSlider',
		'stepping_slider'	=> 'Opc_Paginator_Decorator_SteppingSlider',
		'jumper'			=> 'Opc_Paginator_Decorator_Jumper',
	);
	
	/**
	 * Disabled constructor, because Opc_Paginator is a static class
	 * 		 			 	
	 * @access private		
	 * @return false
	 */		 		
	final private function __construct()
	{
		return false;
	} // end __construct();
	
	/**
	 * Creates a new instance of paginator
	 *		 
	 * @static		 		
	 * @param integer $all The amout of all items
	 * @param integer $limit Items per page		 		 
	 * @return Opc_Paginator_Range New paginator
	 */		 		
	public static function create($all = null, $limit = null)
	{
		return new Opc_Paginator_Range($all, $limit);		
	} // end create();
	
	/**
	 * Registers a new alias for a decorator
	 * 
	 * @static		 	 		
	 * @param string $alias
	 * @param string $className
	 * @return void		 		 
	 */		 		
	public static function registerDecorator($alias, $className)
	{
		self::$_decorators[$alias] = $className;
	} // end registerDecorator();	
	
	/**
	 * Returns a class name for given decorator alias. False when not exists.
	 * 	
	 * @static		 	 		
	 * @param string $alias		 
	 * @return string|false The class name or error
	 */		 		
	public static function getDecoratorClassName($alias)
	{
		if(isset(self::$_decorators[$alias]))
		{
			return self::$_decorators[$alias];
		}
		return false;
	} // end registerDecorator();	
	
	/**
	 * Counts an offset for given page
	 * 	
	 * @static		 	 		
	 * @param integer $page		 
	 * @param integer $limit Items per page 		 
	 * @return integer Offset for given page
	 */		 		
	public static function countOffset($page, $limit)
	{
		return ($page-1) * $limit;
	} // end registerDecorator();	
} // end Opc_Paginator;
