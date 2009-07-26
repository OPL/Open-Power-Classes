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
 */
class Opc_Visit
{
	/**
	 * The internal data store.
	 * @var array
	 */
	private $_data = array();

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
		if(is_null(self::$_instance))
		{
			self::$_instance = new Opc_Visit;
		}
		return self::$_instance;
	} // end getInstance();

	/**
	 * The private class constructor - empty.
	 */
	private function __construct()
	{
		/* null */
	} // end __construct();

	/**
	 * Gets the value of the specified parameter.
	 *
	 * @param String $name The parameter name
	 * @return Mixed
	 */
	public function __get($name)
	{
		if(!isset($this->_data[$name]))
		{
			switch($name)
			{
				case 'IP':
					$this->_data[$name] = $_SERVER['REMOTE_ADDR'];
					break;
				case 'numericIP':
					$this->_data[$name] = ip2long($_SERVER['REMOTE_ADDR']);
					break;
				case 'host':
					if(!isset($_SERVER['REMOTE_HOST']))
					{
						$this->_data[$name] = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
					}
					else
					{
						$this->_data[$name] = $_SERVER['REMOTE_HOST'];
					}
					break;
				case 'protocol':
					$recognized = array('HTTPS' => 'https', 'HTTP' => 'http', 'WAP' => 'wap');
					$this->_data[$name] = 'unknown';
					foreach($recognized as $lookFor => $protocol)
					{
						if(strpos($_SERVER['SERVER_PROTOCOL'], $lookFor) !== false)
						{
							$this->_data[$name] = $protocol;
							break;
						}
					}
					break;
				case 'currentAddress':
				case 'currentFile':
				case 'currentParams':
				case 'currentPath':
				case 'pathInfo':
					$this->_data['currentPath'] = $this->_data['currentAddress'] = $this->_data['currentFile'] = $this->protocol.'://';
					$serverName = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
					$this->_data['currentAddress'] .= $serverName.$_SERVER['REQUEST_URI'];
					$this->_data['currentFile'] .= $serverName.$_SERVER['PHP_SELF'];
					$this->_data['currentParams'] = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['QUERY_STRING'];
					$this->_data['fileName'] = basename($_SERVER['SCRIPT_NAME']);
					$this->_data['currentPath'] = $this->_data['currentFile'];
					if(($pos = strpos($this->_data['currentFile'], $this->_data['fileName'])) !== false)
					{
						$this->_data['currentPath'] = substr($this->_data['currentFile'], 0, $pos);

						// Trim everything after the filename in currentFile. This variable
						// must end at this.
						if($pos + strlen($this->_data['fileName']) < strlen($this->_data['currentFile']))
						{
							$this->_data['currentFile'] = substr($this->_data['currentFile'], 0, $pos + strlen($this->_data['fileName']));
						}
					}
					if(strpos($this->_data['currentAddress'], $this->_data['fileName']) === false)
					{
						// Mod-rewrite used
						$this->_data['pathInfo'] = substr($this->_data['currentAddress'], strlen($this->_data['currentPath']), strlen($this->_data['currentAddress']));
						if($this->_data['pathInfo'][0] != '/' && $this->_data['pathInfo'][0] != '?')
						{
							$this->_data['pathInfo'] = '/'.$this->_data['pathInfo'];
						}
					}
					else
					{
						// No mod-rewrite enter
						$this->_data['pathInfo'] = substr($this->_data['currentAddress'], strlen($this->_data['currentFile']), strlen($this->_data['currentAddress']));
					}
					break;
				case 'referer':
					if(isset($_SERVER['HTTP_REFERER']))
					{
						$this->_data[$name] = $_SERVER['HTTP_REFERER'];
					}
					break;
				case 'cookieServer':
					$this->_data[$name] = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
					break;
				case 'cookiePath':
					$this->_data[$name] = substr($this->currentPath, strpos($this->currentPath, $this->cookieServer) + strlen($this->cookieServer), strlen($this->currentPath));
					break;
				case 'requestMethod':
					$this->_data[$name] = $_SERVER['REQUEST_METHOD'];
					break;
				case 'port':
					$this->_data[$name] = $_SERVER['SERVER_PORT'];
					break;
				case 'secure':
					if($_SERVER['SERVER_PORT'] == 443)
					{
						$this->_data[$name] = true;
					}
					else
					{
						$this->_data[$name] = false;
					}
					break;
				case 'browserName':
				case 'browserVersion':
				case 'browserCode':
				case 'browser':
				case 'OSName':
				case 'OSVersion':
				case 'OSCode':
				case 'OS':
					$this->_data = array_merge($this->_data, $this->_detectBrowser($_SERVER['HTTP_USER_AGENT']));
					$this->_data['browser'] = $this->_data['browserName'].' '.$this->_data['browserVersion'];
					$this->_data['OS'] = $this->_data['OSName'].' '.$this->_data['OSVersion'];
					break;
				case 'languages':
					$this->_data['languages'] = $this->_parseQualityString(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null);
					break;
				case 'mimeTypes':
					$this->_data['mimeTypes'] = $this->_parseQualityString(isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null);
					break;
				default:
					$this->_data[$name] = null;
			}
		}
		return $this->_data[$name];
	} // end __get();

	/**
	 * Parses the quality string from the HTTP request.
	 *
	 * @internal
	 * @param String $string The quality string.
	 * @return Array
	 */
	private function _parseQualityString($string)
	{
		$result = array();
		if(!is_null($string))
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

	/**
	 * Detects the browser.
	 *
	 * @internal
	 * @param String $ua The user agent.
	 * @return Array
	 */
	private function _detectBrowser($ua)
	{
		$browserName = $browserCode = $browserVer = $osName = $osCode = $osVer = '';
		$ua = preg_replace('/FunWebProducts/i', '', $ua);
		switch(true)
		{
			case (preg_match('#Opera[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Opera';
				$browserCode = 'opera';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#(Firefox|Phoenix|Firebird|BonEcho)/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Firefox';
				$browserCode = 'firefox';
				$browserVer = $matches[2];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#MSIE ([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Internet Explorer';
				$browserCode = 'ie';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#Galeon/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Galeon';
				$browserCode = 'galeon';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#Chrome/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Chrome';
				$browserCode = 'chrome';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#Safari/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Safari';
				$browserCode = 'safari';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#(Camino|Chimera)[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Camino';
				$browserCode = 'camino';
				$browserVer = $matches[2];
				$osName = 'MacOS';
				$osCode = 'macos';
				$osVer = 'X';
				break;
			case (preg_match('#Shiira[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Shiira';
				$browserCode = 'shiira';
				$browserVer = $matches[2];
				$osName = 'MacOS';
				$osCode = 'macos';
				$osVer = 'X';
				break;
			case (preg_match('#Dillo[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Dillo';
				$browserCode = 'dillo';
				$browserVer = $matches[1];
				break;
			case (preg_match('#Epiphany/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Epiphany';
				$browserCode = 'epiphany';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#iCab/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'iCab';
				$browserCode = 'icab';
				$browserVer = $matches[1];
				$osName = 'MacOS';
				$osCode = 'macos';
				if(preg_match('#Mac OS X#i', $ua))
				{
					$osVer = 'X';
				}
				break;
			case (preg_match('#K-Meleon/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'K-Meleon';
				$browserCode = 'kmeleon';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#Lynx/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Lynx';
				$browserCode = 'lynx';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#Links \\(([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Links';
				$browserCode = 'lynx';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#ELinks[/ ]([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'ELinks';
				$browserCode = 'lynx';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#ELinks \\(([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'ELinks';
				$browserCode = 'lynx';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#Konqueror/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Konqueror';
				$browserCode = 'konqueror';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#NetPositive/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'NetPositive';
				$browserCode = 'netpositive';
				$browserVer = $matches[1];
				$osName = 'BeOS';
				$osCode = 'beos';
				break;
			case (preg_match('#OmniWeb#i', $ua, $matches)):
				$browserName = 'OmniWeb';
				$browserCode = 'omniweb';
				$osName = 'MacOS';
				$osCode = 'macos';
				$osVer = 'X';
				break;
			case (preg_match('#Netscape[0-9]?/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Netscape';
				$browserCode = 'netscape';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#^Mozilla/5.0#i', $ua) && preg_match('#rv:([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Mozilla';
				$browserCode = 'mozilla';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			case (preg_match('#^Mozilla/([a-zA-Z0-9.]+)#i', $ua, $matches)):
				$browserName = 'Mozilla';
				$browserCode = 'mozilla';
				$browserVer = $matches[1];
				list($osName, $osCode, $osVer) = $this->_detectOS($ua);
				break;
			// SEARCH ENGINES
			case (preg_match('#Googlebot[ \/]([a-zA-Z0-9\.]+)#i', $ua, $matches)):
				$browserName = 'Google';
				$browserCode = 'googlebot';
				$browserVer = $matches[1];
				$osName = 'Search engine';
				$osCode = 'webcrawler';
				$osVer = '';
				break;
			case (preg_match('#Scooter[ \/]([a-zA-Z0-9\.]+)#i', $ua, $matches)):
				$browserName = 'Altavista';
				$browserCode = 'scooter';
				$browserVer = $matches[1];
				$osName = 'Search engine';
				$osCode = 'webcrawler';
				$osVer = '';
				break;
			case (preg_match('#MSNBOT[ \/]([a-zA-Z0-9\.]+)#i', $ua, $matches)):
				$browserName = 'MSN Search';
				$browserCode = 'msnbot';
				$browserVer = $matches[1];
				$osName = 'Search engine';
				$osCode = 'webcrawler';
				$osVer = '';
				break;
			case (preg_match('#Lycos_Spider_\(T\-Rex\)#i', $ua, $matches)):
				$browserName = 'Lycos';
				$browserCode = 'lycos_spider';
				$browserVer = '';
				$osName = 'Search engine';
				$osCode = 'webcrawler';
				$osVer = '';
				break;
			case (preg_match('#archive\.org_bot#i', $ua, $matches)):
				$browserName = 'Archive.org';
				$browserCode = 'archive.org_bot';
				$browserVer = '';
				$osName = 'Search engine';
				$osCode = 'webcrawler';
				$osVer = '';
				break;
		}

		return array(
			'browserName' => $browserName,
			'browserCode' => $browserCode,
			'browserVersion' => $browserVer,
			'OSName' => $osName,
			'OSCode' => $osCode,
			'OSVersion' => $osVer
		);
	} // end _detectBrowser();

	/**
	 * Detects the operating system.
	 *
	 * @internal
	 * @param String $ua The user agent string
	 * @return Array
	 */
	private function _detectOS($ua)
	{
		$osName = $osCode = $osVer = '';
		switch(true)
		{
			case (preg_match('/Windows 95/i', $ua) || preg_match('/Win95/', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				$osVer = '95';
				break;
			case (preg_match('/Windows NT 5.0/i', $ua) || preg_match('/Windows 2000/i', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				$osVer = '2000';
				break;
			case (preg_match('/Win 9x 4.90/i', $ua) || preg_match('/Windows ME/i', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				$osVer = 'ME';
				break;
			case (preg_match('/Windows.98/i', $ua) || preg_match('/Win98/i', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				$osVer = '98';
				break;
			case (preg_match('/Windows (NT 5\.1|XP)/i', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				$osVer = 'XP';
				break;
			case (preg_match('/Windows NT 5.2/i', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				if (preg_match('/Win64/i', $ua))
				{
					$osVer = 'XP 64 bit';
				}
				else
				{
					$osVer = 'Server 2003';
				}
				break;
			case (preg_match('/Windows NT 6\.0/i', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				if(preg_match('/Win64/i', $ua))
				{
					$osVer = 'Vista 64 bit';
				}
				else
				{
					$osVer = 'Vista';
				}
				break;
			case (preg_match('/Mac_PowerPC/i', $ua)):
				$osName = 'MacOS';
				$osCode = 'macos';
				break;
			case (preg_match('/Windows NT 4.0/i', $ua) || preg_match('/WinNT4.0/i', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				$osVer = 'NT 4.0';
				break;
			case (preg_match('/Windows NT/i', $ua) || preg_match('/WinNT/i', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				$osVer = 'NT';
				break;
			case (preg_match('/Windows CE/i', $ua)):
				$osName = 'Windows';
				$osCode = 'windows';
				$osVer = 'CE';
				if(preg_match('/PPC/i', $ua))
				{
					$osName = 'Microsoft PocketPC';
					$osCode = 'windows';
					$osVer = '';
				}

				if(preg_match('/smartphone/i', $ua))
				{
					$osName = 'Microsoft Smartphone';
					$osCode = 'windows';
					$osVer = '';
				}
				break;
			case (preg_match('/Linux/i', $ua)):
				$osName = 'Linux';
				$osCode = 'linux';
				switch(true)
				{
					case (preg_match('#Debian#i', $ua)):
						$osCode = 'debian';
						$osName = 'Debian GNU/Linux';
						break;
					case (preg_match('#Mandrake#i', $ua)):
						$osCode = 'mandrake';
						$osName = 'Mandrake Linux';
						break;
					case (preg_match('#SuSE#i', $ua)):
						$osCode = 'suse';
						$osName = 'SuSE Linux';
						break;
					case (preg_match('#Novell#i', $ua)):
						$osCode = 'novell';
						$osName = 'Novell Linux';
						break;
					case (preg_match('#Ubuntu#i', $ua)):
						$osCode = 'ubuntu';
						$osName = 'Ubuntu Linux';
						break;
					case (preg_match('#ARCH#i', $ua)):
						$osCode = 'arch';
						$osName = 'Arch Linux';
						break;
					case (preg_match('#Red ?Hat#i', $ua)):
						$osCode = 'redhat';
						$osName = 'RedHat Linux';
						break;
					case (preg_match('#Gentoo#i', $ua)):
						$osCode = 'gentoo';
						$osName = 'Gentoo Linux';
						break;
					case (preg_match('#Fedora#i', $ua)):
						$osCode = 'fedora';
						$osName = 'Fedora Linux';
						break;
					case (preg_match('#MEPIS#i', $ua)):
						$osName = 'MEPIS Linux';
						break;
					case (preg_match('#Knoppix#i', $ua)):
						$osName = 'Knoppix Linux';
						break;
					case (preg_match('#Slackware#i', $ua)):
						$osCode = 'slackware';
						$osName = 'Slackware Linux';
						break;
					case (preg_match('#Xandros#i', $ua)):
						$osName = 'Xandros Linux';
						break;
					case (preg_match('#Kanotix#i', $ua)):
						$osName = 'Kanotix Linux';
						break;
				}
				break;
			case preg_match('/FreeBSD/i', $ua):
				$osName = 'FreeBSD';
				$osCode = 'freebsd';
				break;
			case preg_match('/NetBSD/i', $ua):
				$osName = 'NetBSD';
				$osCode = 'netbsd';
				break;
			case preg_match('/OpenBSD/i', $ua):
				$osName = 'OpenBSD';
				$osCode = 'openbsd';
				break;
			case preg_match('/IRIX/i', $ua):
				$osName = 'SGI IRIX';
				$osCode = 'sgi';
				break;
			case preg_match('/SunOS/i', $ua):
				$osName = 'Solaris';
				$osCode = 'sun';
				break;
			case preg_match('/Mac OS X/i', $ua):
				$osName = 'Mac OS';
				$osCode = 'macos';
				$osVer = 'X';
				break;
			case preg_match('/Macintosh/i', $ua):
				$osName = 'Mac OS';
				$osCode = 'macos';
				break;
			case preg_match('/Unix/i', $ua):
				$osName = 'UNIX';
				$osCode = 'unix';
				break;
		}

		return array($osName, $osCode, $osVer);
	} // end _detectOS();
} // end Opc_Visit;
