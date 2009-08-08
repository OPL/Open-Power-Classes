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
 * 
 * @author Amadeusz "megawebmaster" Starzykiewicz
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Opc_View_Cache implements Opt_Caching_Interface
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
	 * @var string
	 */
	private $_cacheDir = null;

	/**
	 * The caching extra key.
	 *
	 * @var string
	 */
	private $_key = null;

	/**
	 * The caching validity time.
	 *
	 * @var integer
	 */
	private $_time = null;

	/**
	 * Cache file name.
	 *
	 * @var string
	 */
	private $_filename = null;

	/**
	 * Determines if file was read or not.
	 *
	 * @var boolean
	 */
	private $_isReadAlready = false;

	/**
	 * File handle.
	 *
	 * @var resource|null
	 */
	private $_fileHandle = null;

	/**
	 * Presents if the view has dynamic content or not.
	 *
	 * @var boolean
	 */
	private $_dynamic = false;

	/**
	 * Creates a new caching object and connects to it.
	 * 
	 * @param Opt_View $view The view 
	 */
	public function __construct(Opt_View $view)
	{
		if(!Opl_Registry::exists('opc'))
		{
			throw new Opc_ClassInstanteNotExists_Exception;
		}
		$this->_opc = Opl_Registry::get('opc');
		
		$this->_view = $view;
	} // end __construct();
	
	/**
	 * Sets the extra key for the cache.
	 * 
	 * @param string|null $key The extra key
	 */
	public function setKey($key)
	{
		$this->_key = $key;
	} // end setKey();
	
	/**
	 * Sets the expiry time for the specified caching object.
	 * 
	 * @param integer $time The new expiry time. 
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
	 * 
	 * @param string $dir New directory
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
	 * 
	 * @return string|null
	 */
	public function getKey()
	{
		return $this->_key;
	} // end getKey();
	
	/**
	 * Returns the current expiry time for the cache.
	 * 
	 * @return integer
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
	 * 
	 * @return string
	 */
	public function getCacheDir()
	{
		if($this->_cacheDir === null)
		{
			$this->_cacheDir = $this->_opc->cacheDir;
		}
		return $this->_cacheDir;
	} // end getCacheDir();

	/**
	 * Gets the cache filename with special key.
	 *
	 * @return string
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
			$this->_fileHandle = @fopen($this->getCacheDir().$this->_getFilename(), 'r');
			if($this->_fileHandle === false)
			{
				return false;
			}
			$header = fgets($this->_fileHandle);
			if($header[0] == '<')
			{
				$header = str_replace(array('<'.'?php /* ','*/ ?>'), '', $header);
			}
			$header = unserialize($header);
			if(!is_array($header))
			{
				/* When header is not an array it means it is wrong, so file must be deleted
				   because of that and because it could be bad or unauthorized changed */
				unlink($this->getCacheDir().$this->_getFilename());
				$this->_fileHandle = null;
				return false;
			}
			if($header['timestamp'] < (time() - $header['expire']))
			{
				/* When cache file is old it should be deleted ;) */
				unlink($this->getCacheDir().$this->_getFilename());
				$this->_fileHandle = null;
				return false;
			}
			$this->_dynamic = $header['dynamic'];
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
		unlink($this->getCacheDir().$this->_getFilename());
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
			if($this->_dynamic)
			{
				return $this->getCacheDir().$this->_getFilename();
			}
			else
			{
				while(!feof($this->_fileHandle))
				{
					$buf = fgets($this->_fileHandle, 2048);
					echo $buf;
				}
			}
			return true;
		}
		else
		{
			ob_start();
			$tpl = Opl_Registry::get('opt');
			$tpl->setBufferState('cache',true);
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
			'expire' => $this->getExpiryTime(),
			'dynamic' => $view->hasDynamicContent()?'true':'false'
		);
		$header = '<'.'?php /* '.serialize($header).'*/ ?>'."\n";
		$tpl = Opl_Registry::get('opt');
		$tpl->setBufferState('cache',false);
		
		if($view->hasDynamicContent())
		{
			$buffer = $view->getOutputBuffers();
			$dyn = file_get_contents($tpl->compileDir.$this->_view->_convert($this->_view->getTemplate()).'.dyn');
			if($dyn !== false)
			{
				$dynamic = unserialize($dyn);
				unset($dyn);
			}
			else
			{
				throw new Opc_View_CacheInvalidDynamicContent_Exception($this->_view->getTemplate());
			}
			$content = '';
			for($i = 0, $endI = count($buffer); $i<$endI; $i++)
			{
				$content .= $buffer[$i];
				$content .= $dynamic[$i];
			}
			if(file_put_contents($this->getCacheDir().$this->_getFilename(), $header.$content.ob_get_flush()) === false)
			{
				throw new Opc_View_CacheCannotSaveFile_Exception($this->getCacheDir());
			}
		}
		else
		{
			if(file_put_contents($this->getCacheDir().$this->_getFileName(), $header.ob_get_contents()) === false)
			{
				throw new Opc_View_CacheCannotSaveFile_Exception($this->getCacheDir());
			}
		}
	} // end templateCacheStop();
} // end Opc_View_Cache;
