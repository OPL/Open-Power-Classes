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
	private $_expiryTime = null;

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
	 * @param array $options Options
	 */
	public function __construct(array $options = array())
	{
		if(!Opl_Registry::exists('opc'))
		{
			throw new Opc_ClassInstanceNotExists_Exception;
		}
		$this->_opc = Opl_Registry::get('opc');
		if(isset($options['cacheDir']))
		{
			$this->setCacheDir($options['cacheDir']);
		}
		if(isset($options['expiryTime']))
		{
			$this->setExpiryTime($options['expiryTime']);
		}
		if(isset($options['key']))
		{
			$this->setKey($options['key']);
		}
	} // end __construct();
	
	/**
	 * Sets the extra key for the cache. Deleting key is setting it to null.
	 * 
	 * @param string|null $key The extra key
	 */
	public function setKey($key)
	{
		$this->_key = (string)$key;
	} // end setKey();

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
	 * Sets the expiry time for the specified caching object.
	 * 
	 * @param integer $time The new expiry time. 
	 */
	public function setExpiryTime($time)
	{
		if(is_integer($time) || is_numeric($time))
		{
			$this->_expiryTime = (int)$time;
		}
		else
		{
			throw new Opc_InvalidArgumentType_Exception(gettype($time), 'integer');
		}
	} // end setExpiryTime();

	/**
	 * Returns the current expiry time for the cache.
	 *
	 * @return integer
	 */
	public function getExpiryTime()
	{
		if($this->_expiryTime === null)
		{
			$this->_expiryTime = $this->_opc->expiryTime;
		}
		return $this->_expiryTime;
	} // end getExpiryTime();

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
	 * @param Opt_View $view View.
	 * @return string
	 */
	private function _getFilename(Opt_View $view)
	{
		return ($this->_key !== null?$this->_key.'_':'').$view->getTemplate().'.cch';
	} // end _getFilename();

	/**
	 * Checks if the specified view is cached.
	 *
	 * @param Opt_View $view View.
	 * @return boolean
	 */
	public function isCached(Opt_View $view)
	{
		if(!$this->_isReadAlready)
		{
			$this->_isReadAlready == true;
			$this->_fileHandle = @fopen($this->getCacheDir().$this->_getFilename($view), 'r');
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
				unlink($this->getCacheDir().$this->_getFilename($view));
				$this->_fileHandle = null;
				return false;
			}
			if($header['timestamp'] < (time() - $header['expire']))
			{
				/* When cache file is too old it should be deleted ;) */
				unlink($this->getCacheDir().$this->_getFilename($view));
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
	 *
	 * @param Opt_View $view View
	 * @return boolean
	 */
	public function clear(Opt_View $view)
	{
		return unlink($this->getCacheDir().$this->_getFilename($view));
	} // end clear();

	/**
	 * The interface method of Opt_Caching_Interface. Decides
	 * whether the specified view must be cached.
	 *
	 * @param Opt_View $view The cached view.
	 */
	public function templateCacheStart(Opt_View $view)
	{
		if($this->isCached($view))
		{
			if($this->_dynamic == 'true')
			{
				return $this->getCacheDir().$this->_getFilename($view);
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
			$dyn = file_get_contents($tpl->compileDir.$view->_convert($view->getTemplate()).'.dyn');
			if($dyn !== false)
			{
				$dynamic = unserialize($dyn);
				unset($dyn);
			}
			else
			{
				throw new Opc_View_Cache_InvalidDynamicContent_Exception($view->getTemplate());
				return false;
			}
			$content = '';
			for($i = 0, $endI = count($buffer); $i<$endI; $i++)
			{
				$content .= $buffer[$i];
				$content .= $dynamic[$i];
			}
			if(file_put_contents($this->getCacheDir().$this->_getFilename($view), $header.$content.ob_get_flush()) === false)
			{
				throw new Opc_View_Cache_CannotSaveFile_Exception($this->getCacheDir());
				return false;
			}
		}
		else
		{
			if(file_put_contents($this->getCacheDir().$this->_getFileName($view), $header.ob_get_contents()) === false)
			{
				throw new Opc_View_Cache_CannotSaveFile_Exception($this->getCacheDir());
				return false;
			}
		}
	} // end templateCacheStop();
} // end Opc_View_Cache;
