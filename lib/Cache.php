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
 * The class represents a caching system for Open Power Template 2.
 */
class Opc_Cache implements Opt_Caching_Interface
{
	/**
	 * The view that the caching system is connected to.
	 *
	 * @var Opt_View
	 */
	private $_view;

	/**
	 * The Opc_Class object.
	 *
	 * @var Opc_Class
	 */
	private $_opc;

	/**
	 * Cache dir.
	 * 
	 * @var String
	 */
	private $_cacheDir = null;

	/**
	 * The caching extra key.
	 *
	 * @var String
	 */
	private $_key = null;

	/**
	 * The caching validity time.
	 *
	 * @var Integer
	 */
	private $_time = null;

	/**
	 * Cache file name.
	 *
	 * @var String
	 */
	private $_filename = null;

	/**
	 * Determines if file was read or not.
	 *
	 * @var Boolean
	 */
	private $_isReadAlready = false;

	/**
	 * File handle.
	 *
	 * @var Resource|Null
	 */
	private $_fileHandle = null;

	/**
	 * Creates a new caching object and connects to it.
	 * 
	 * @param Opt_View $view The view 
	 */
	public function __construct(Opt_View $view)
	{
		$this->_opc = Opl_Registry::get('opc');
		$this->_view = $view;
	} // end __construct();
	
	/**
	 * Sets the extra key for the cache.
	 * @param String|Null $key The extra key
	 */
	public function setKey($key)
	{
		$this->_key = $key;
	} // end setKey();
	
	/**
	 * Sets the expiry time for the specified caching object.
	 * 
	 * @param Integer $time The new expiry time. 
	 */
	public function setExpiryTime($time)
	{
		if(is_integer($time))
		{
			$this->_time = $time;
		}
		else
		{
			throw new Opc_InvalidArgumentType_Exception(gettype($time), 'integer');
		}
	} // end setExpiryTime();

	/**
	 * Sets directory for cache files.
	 * @param String $dir New directory
	 */
	public function setCacheDir($dir)
	{
		if($dir[strlen($dir)-1] != DIRECTORY_SEPARATOR)
		{
			$dir .= DIRECTORY_SEPARATOR;
		}
		
		// Prevention against current directory changes in Apache
		// which affects destructors. We avoid it by switching to the
		// absolute path.

		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false)
		{
			$dir = realpath($dir).DIRECTORY_SEPARATOR;
		}
		$this->_cacheDir = $dir;
	} // end setCacheDir();
	
	/**
	 * Returns the current extra caching key.
	 * @return String|Null
	 */
	public function getKey()
	{
		return $this->_key;
	} // end getKey();
	
	/**
	 * Returns the current expiry time for the cache.
	 * @return Integer
	 */
	public function getExpiryTime()
	{
		if($this->_time === null)
		{
			$this->_time = $this->_opc->expiryTime;
		}
		return $this->_time;
	} // end getExpiryTime();

	/**
	 * Returns cache directory.
	 * @return String
	 */
	public function getCacheDir()
	{
		if($this->_cacheDir === null)
		{
			$this->_cacheDir = $this->_opc->cacheDir;
		}
		return $this->_cacheDir;
	}

	/**
	 * Gets the cache filename with special key.
	 *
	 * @return String
	 */
	private function _getFilename()
	{
		if($this->_filename == null)
		{
			$this->_filename = $this->_key.'_'.$this->_view->getTemplate().'.cch';
		}
		return $this->_filename;
	} // end _getFilename();

	/**
	 * Checks if the specified view is cached.
	 *
	 * @return boolean
	 */
	public function isCached()
	{
		if(!$this->_isReadAlready)
		{
			$this->_isReadAlready == true;
			$this->_fileHandle = @fopen($this->_cacheDir.$this->_getFilename(), 'r');
			if($this->_fileHandle === false)
			{
				return false;
			}
			$header = fgets($this->_fileHandle);
			if($header[0] == '<')
			{
				$header = str_replace(array('<'.'?cacheHeader ','?>'), '', $header);
			}
			$header = unserialize($header);
			if(!is_array($header))
			{
				/* When header is not an array it means it is wrong, so file must be deleted
				   because of that and because it could be bad or unauthorized changed */
				unlink($this->_cacheDir.$this->_getFilename());
				$this->_fileHandle = null;
				return false;
			}
			if($header['timestamp'] < (time() - $header['expiry']))
			{
				/* When cache file is old it should be deleted ;) */
				unlink($this->_cacheDir.$this->_getFilename());
				$this->_fileHandle = null;
				return false;
			}
			return true;
		}
		else
		{
			if($this->_fileHandle !== null)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	} // end isCached();

	/**
	 * Clears the cache for the specified view.
	 */
	public function clear()
	{
		unlink($this->_cacheDir.$this->_getFilename());
	} // end clear();

	/**
	 * The interface method of Opt_Caching_Interface. Decides
	 * whether the specified view must be cached.
	 *
	 * @param Opt_View $view The cached view.
	 */
	public function templateCacheStart(Opt_View $view)
	{
		if($this->isCached())
		{
			while(!feof($this->_fileHandle))
			{
				$buf = fgets($this->_fileHandle, 2048);
				echo $buf;
			}
			return true;
		}
		else
		{
			ob_start();
			ob_implicit_flush(false);
			return false;
		}
	} // end templateCacheStart();

	/**
	 * The interface method of Opt_Caching_Interface. Finalizes
	 * the caching of the view.
	 *
	 * @param Opt_View $view The cached view.
	 */
	public function templateCacheStop(Opt_View $view)
	{
		$header = array(
			'timestamp' => time(),
			'expire' => $this->getExpiryTime()
		);
		$header = '<'.'?cacheHeader '.serialize($header).'?>'."\n";

		// TODO implement dynamic templates
		file_put_contents($this->getCacheDir().$this->_getFileName, $header.ob_get_clean());
	} // end templateCacheStop();
} // end Opc_Cache;
