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
namespace Opc;
use Opc\Core;
use Opc\Paginator\Range;
/**
 * A factory and global configuration class for Paginator.
 * 
 * @author Jacek "eXtreme" Jędrzejewski
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Paginator
{
	/**
	 * List of default decorator aliases
	 * @static
	 * @access private
	 * @var array
	 */
	protected static $_decorators = array(
		'all'				=> 'Opc\Paginator\Decorator\All',
		'slider'			=> 'Opc\Paginator\Decorator\Slider',
		'dotting_slider'	=> 'Opc\Paginator\Decorator\DottingSlider',
		'stepping_slider'	=> 'Opc\Paginator\Decorator\SteppingSlider',
		'range_slider'		=> 'Opc\Paginator\Decorator\RangeSlider',
		'jumper'			=> 'Opc\Paginator\Decorator\Jumper',
	);
	
	/**
	 * Disabled constructor, because Opc\Paginator is a static class
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
	 * @param \Opc\Core $opc The main configuration class
	 * @param integer $all The amout of all items
	 * @param integer $limit Items per page
	 * @return Opc\Paginator\Range New paginator
	 */
	public static function create(Core $opc, $all = null, $limit = null)
	{
		return new Range($opc, $all, $limit);
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
		if($page < 1)
		{
			return 0;
		}
		return ($page-1) * $limit;
	} // end registerDecorator();
} // end Paginator;
