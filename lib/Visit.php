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
 * An utility class that serves various information about the current
 * request.
 * 
 * @author Tomasz "Zyx" Jędrzejewski
 * @author Jacek "eXtreme" Jędrzejewski
 */
class Opc_Visit
{
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $ip;
	/**
	 * 
	 * @access public
	 * @var integer
	 */
	protected $numericIp;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $host;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $protocol;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $referrer;
	/**
	 * 
	 * @access public
	 * @var integer
	 */
	protected $port;
	/**
	 * 
	 * @access public
	 * @var boolean
	 */
	protected $secure;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $requestMethod;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $userAgentString;
	/**
	 * 
	 * @access public
	 * @var array
	 */
	protected $userAgent;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $cookieServer;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $cookiePath;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $currentAddress;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $currentFile;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $currentParams;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $currentPath;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $basePath;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $pathInfo;
	/**
	 * 
	 * @access public
	 * @var string
	 */
	protected $fileName;
	
	/**
	 * List of public fields.
	 * @internal
	 * @var array
	 */
	private $_fields = array(
				'ip', 'numericIp', 'host', 'protocol', 'referrer', 'port', 'secure', 'requestMethod', 
				'userAgentString', 'userAgent', 'cookieServer', 'cookiePath', 'currentAddress', 'currentFile', 
				'currentParams', 'currentPath', 'basePath', 'pathInfo', 'fileName'
			);

	/**
	 * The singleton instance
	 * @static
	 * @var Opc_Visit
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
			self::$_instance = new Opc_Visit;
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
	
	/**
	 * @return array
	 */
	public function toArray()
	{
		$return = array();
		
		foreach($this->_fields as $field)
		{
			$return[$field] = $this->get($field);
		}
		
		return $return;
	} // end toArray();
	
	/**
	 * Magic function so that $obj->key = "value" will NOT work.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return true
	 */
	final public function __set($key, $value)
	{
		if($key[0] == '_' || !isset($this->$key))
		{
			throw new Opc_OptionNotExists_Exception($key, __CLASS__);
		}
		
		throw new Opc_OptionReadOnly_Exception($key, __CLASS__);
	} // end __set();
	
	/**
	 * Magic function so that $obj->key will work.
	 *
	 * @param string $key
	 * @return mixed
	 */
	final public function __get($key)
	{
		return $this->get($key);
	} // end __get();

	/**
	 * Gets the value of the specified parameter.
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		// Filter out private fields..
		if($key[0] == '_')
		{
			throw new Opc_OptionNotExists_Exception($key, __CLASS__);
		}
		
		// At first, we check if the data is persisted in a variable.
		if($this->$key != null)
		{
			return $this->$key;
		}
		
		// Now, we can fill empty variables.
		switch($key)
		{
			case 'ip':
				$this->ip = $_SERVER['REMOTE_ADDR'];
				break;
			case 'numericIp':
				$this->numericIp = ip2long($_SERVER['REMOTE_ADDR']);
				break;
			case 'host':
				if(empty($_SERVER['REMOTE_HOST']))
				{
					$this->host = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
				}
				else
				{
					$this->host = $_SERVER['REMOTE_HOST'];
				}
				break;
			case 'protocol':
				$recognized = array('HTTPS' => 'https', 'HTTP' => 'http', 'WAP' => 'wap');
				$this->protocol = 'unknown';
				foreach($recognized as $lookFor => $protocol)
				{
					if(strpos($_SERVER['SERVER_PROTOCOL'], $lookFor) !== false)
					{
						$this->protocol = $protocol;
						break;
					}
				}
				break;
			case 'referrer':
				$this->referrer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
				break;
			case 'requestMethod':
				$this->requestMethod = $_SERVER['REQUEST_METHOD'];
				break;
			case 'userAgentString':
				$this->userAgentString = $_SERVER['HTTP_USER_AGENT'];
				break;
			case 'userAgent':
				$this->userAgent = Opc_Visit_UserAgent::getInstance()->analyze($_SERVER['HTTP_USER_AGENT']);
				break;
			case 'port':
				$this->port = $_SERVER['SERVER_PORT'];
				break;
			case 'secure':
				$this->secure = $_SERVER['SERVER_PORT'] == 443;
				break;
			case 'languages':
				$this->languages = $this->_parseQualityString(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null);
				break;
			case 'mimeTypes':
				$this->mimeTypes = $this->_parseQualityString(isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null);
				break;
			case 'cookieServer':
				$this->cookieServer = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
				break;
			case 'cookiePath':
				$this->cookiePath = substr($this->get('currentPath'), strpos($this->get('currentPath'), $this->get('cookieServer')) + strlen($this->get('cookieServer')), strlen($this->get('currentPath')));
				break;
				
			case 'currentAddress':
			case 'currentFile':
			case 'currentParams':
			case 'currentPath':
			case 'basePath':
			case 'pathInfo':
				$this->currentPath = $this->currentAddress = $this->currentFile = $this->get('protocol').'://';
				$serverName = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
				
				$this->currentAddress .= $serverName.$_SERVER['REQUEST_URI'];
				$this->currentFile .= $serverName.$_SERVER['PHP_SELF'];
				$this->currentParams = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['QUERY_STRING'];
				$this->fileName = basename($_SERVER['SCRIPT_NAME']);
				$this->currentPath = $this->currentFile;
				if(($pos = strpos($this->currentFile, $this->fileName)) !== false)
				{
					$this->currentPath = substr($this->currentFile, 0, $pos);
					
					// Trim everything after the filename in currentFile. This variable
					// must end at this.
					if($pos + strlen($this->fileName) < strlen($this->currentFile))
					{
						$this->currentFile = substr($this->currentFile, 0, $pos + strlen($this->fileName));
					}
				}
				if(strpos($this->currentAddress, $this->fileName) === false)
				{
					// Mod-rewrite used
					$this->pathInfo = substr($this->currentAddress, strlen($this->currentPath), strlen($this->currentAddress));
					if($this->pathInfo[0] != '/' && $this->pathInfo[0] != '?')
					{
						$this->pathInfo = '/'.$this->pathInfo;
					}
				}
				else
				{
					// No mod-rewrite enter
					$this->pathInfo = substr($this->currentAddress, strlen($this->currentFile), strlen($this->currentAddress));
				}
				$this->basePath = substr($this->currentPath, strpos($this->currentPath, $serverName) + strlen($serverName));
				break;
					
			default:
				throw new Opc_OptionNotExists_Exception($key, get_class($this));
				break;
		}
		
		return $this->$key;
	} // end get();
	
	/**
	 * Clears all public variables.
	 * 
	 * @return void
	 */
	public function reset()
	{
		foreach($this->_fields as $field)
		{
			$this->$field = null;
		}
	} // end reset();
	
	/**
	 * Parses the quality string from the HTTP request.
	 *
	 * @internal
	 * @param string $string The quality string.
	 * @return array
	 */
	private function _parseQualityString($string)
	{
		$result = array();
		if($string != null)
		{
			$list = explode(',', $string);
			$proc = array();
			foreach($list as $item)
			{
				$item = trim($item);
				if(strpos($item, ';') !== false)
				{
					$range = explode(';', $item);
					$proc[] = array('symbol' => $range[0], 'q' => (float)trim($range[1], 'q='));
				}
				else
				{
					$proc[] = array('symbol' => $item, 'q' => 1);
				}
			}
			usort($proc, array($this, '_quality'));
			foreach($proc as $lang)
			{
				$result[] = $lang['symbol'];
			}
		}
		return $result;
	} // end _parseQualityString();

	/**
	 * The quality comparator for the sorting algorithm.
	 *
	 * @internal
	 * @param Array $a
	 * @param Array $b
	 * @return Integer
	 */
	private function _quality(&$a, &$b)
	{
		return (int)(($b['q'] - $a['q'])*100);
	} // end _quality();
} // end Opc_Visit;
