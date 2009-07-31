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
 * Browser and OS detector.
 * 
 * @author Jacek "eXtreme" Jędrzejewski
 */
class Opc_Visit_UserAgent
{
	/**
	 * The singleton instance
	 * @static
	 * @var Opc_Visit_UserAgent
	 */
	static private $_instance = null;
	
	/**
	 * Singleton implementation
	 *
	 * @static
	 * @return Opc_Visit
	 */
	static public function getInstance()
	{
		if(self::$_instance == null)
		{
			self::$_instance = new Opc_Visit_UserAgent;
		}
		return self::$_instance;
	} // end getInstance();

	/**
	 * The private class constructor - empty.
	 */
	final private function __construct()
	{
		/* null */
	} // end __construct();
	
	public function analyze($ua)
	{
		$return = array('browser' => array(), 'os' => array());
		
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
		
		$browsers = parse_ini_file($dir.'browsers.ini', true);
		
		foreach($browsers as $regex => $browser)
		{
			if(preg_match($regex, $ua, $matches))
			{
				if(isset($browser['matches']) && is_array($browser['matches']))
				{
					foreach($browser['matches'] as $i => $key)
					{
						$return['browser'][$key] = $matches[(int)$i];
					}
				}
				unset($browser['matches']);
				foreach($browser as $key => $value)
				{
					$return['browser'][$key] = $value;
				}
				break;
			}
		}
		
		if(empty($return['browser']['name']))
		{
			$return['browser'] = array(
				'name' => 'unknown',
				'version' => ''
			);
		}
		
		$systems = parse_ini_file($dir.'systems.ini', true);
		
		foreach($systems as $regex => $system)
		{
			if(preg_match($regex, $ua, $matches))
			{
				if(isset($system['matches']) && is_array($system['matches']))
				{
					foreach($system['matches'] as $i => $key)
					{
						$return['os'][$key] = $matches[(int)$i];
					}
				}
				unset($system['matches']);
				foreach($system as $key => $value)
				{
					$return['os'][$key] = $value;
				}
				break;
			}
		}
		
		if(empty($return['os']['os']))
		{
			$return['os'] = array(
				'os' => 'unknown',
				'name' => ''
			);
		}
		
		return $return;
	} // end analyze();
}