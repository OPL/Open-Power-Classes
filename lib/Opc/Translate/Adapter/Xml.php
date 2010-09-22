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
namespace Opc\Translate\Adapter;
use Opc\Translate\Adapter;
use Opc\Translate\Adapter\Exception as Opc_Translate_Adapter_Exception;
use \Opl_Registry;
/**
 * The translation adapter for XML files representing the message format.
 * Needs to be completed.
 *
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Xml implements Adapter
{
	protected
		/**
		 * Opc\Core instance
		 * @var Opc\Core
		 */
		$_opc = null,
		/**
		 * Language files directory.
		 * @var string
		 */
		$_directory = null,
		/**
		 * File existence checking state.
		 * @var boolean
		 */
		$_fileExistsCheck = null,
		/**
		 * We want to compile result data?
		 * @var boolean $_compileResult
		 */
		$_compileResult = null,
		/**
		 * Directory to store compiled data.
		 * @var string Directory
		 */
		$_compileResultDirectory = null,
		/**
		 * Loaded translation.
		 * @var array
		 */
		$_messages = null,
		/**
		 * Assigned from template values.
		 * @var array
		 */
		$_assigned = null;

	/**
	 * Creates the adapter and applies the options.
	 * 
	 * @param array $options The adapter options.
	 */
	public function __construct(array $options = array())
	{
		if(!Opl_Registry::exists('opc'))
		{
			throw new Opc_Translate_Adapter_Exception('Opc\Core class not exists!');
		}
		$this->_opc = Opl_Registry::get('opc');
		if(isset($options['directory']))
		{
			$this->setDirectory($options['directory']);
		}
		if(isset($options['fileExistsCheck']))
		{
			$this->_fileExistsCheck = (boolean)$options['fileExistsCheck'];
		}
		if(isset($options['compileResult']))
		{
			$this->setCompileResult($options['compileResult']);
		}
		if(isset($options['compileResultDirectory']))
		{
			$this->setCompileResultDirectory($options['compileResultDirectory']);
		}
	} // end __construct();

	/**
	 * Sets the message directory.
	 * 
	 * @param string $directory The new directory.
	 */
	public function setDirectory($directory)
	{
		if($directory != '')
		{
			if($directory[strlen($directory)-1] != DIRECTORY_SEPARATOR)
			{
				$directory .= DIRECTORY_SEPARATOR;
			}
		}

		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false)
		{
			$directory = realpath($directory).DIRECTORY_SEPARATOR;
		}
		$this->_directory = $directory;
	} // end setDirectory();
	
	/**
	 * Returns files directory.
	 * @return string Directory
	 */
	public function getDirectory()
	{
		if($this->_directory === null)
		{
			throw new Opc_Translate_Adapter_Exception('Translation adapter is not configured properly: lack of files directory!');
		}
		return $this->_directory;
	} // end getDirectory();

	/**
	 * Sets file exists checking on or off.
	 * @param boolean $state State
	 */
	public function setFileExistsCheck($state)
	{
		$this->_fileExistsCheck = (bool)$state;
	} // end setFileExistsCheck();

	/**
	 * Returns state of file existence checking.
	 * @return boolean State
	 */
	public function getFileExistsCheck()
	{
		if($this->_fileExistsCheck === null)
		{
			$this->_fileExistsCheck = $this->_opc->translateFileExistsCheck;
		}
		return $this->_fileExistsCheck;
	} // end getFileExistsCheck();

	/**
	 * Sets result compilation on or off.
	 * @param boolean $state State
	 */
	public function setCompileResult($state)
	{
		$this->_compileResult = (bool)$state;
	} // end setFileExistsCheck();

	/**
	 * Returns state of compiling results feature.
	 * @return boolean State
	 */
	public function getCompileResult()
	{
		if($this->_compileResult === null)
		{
			$this->_compileResult = $this->_opc->translateCompileResult;
		}
		return $this->_compileResult;
	} // end getFileExistsCheck();

	/**
	 * Sets compiled data files storing directory.
	 * @param string $directory Directory
	 */
	public function setCompileResultDirectory($directory)
	{
		if($directory != '')
		{
			if($directory[strlen($directory)-1] != DIRECTORY_SEPARATOR)
			{
				$directory .= DIRECTORY_SEPARATOR;
			}
		}

		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false)
		{
			$directory = realpath($directory).DIRECTORY_SEPARATOR;
		}
		$this->_compileResultDirectory = $directory;
	} // end setCompileResultDirectory();

	/**
	 * Returns compiled data files directory.
	 * @return string Directory
	 */
	public function getCompileResultDirectory()
	{
		if($this->_compileResultDirectory === null)
		{
			if(strpos($this->_opc->translateCompileResultDirectory, '!') === 0)
			{
				$this->_compileResultDirectory = $this->_directory.DIRECTORY_SEPARATOR.$this->_opc->translateCompileResultDirectory.DIRECTORY_SEPARATOR;
			}
			else
			{
				$this->_compileResultDirectory = $this->_opc->translateCompileResultDirectory;
			}
			$this->_compileResultDirectory = $this->_opc->translateCompileResultDirectory;
		}
		return $this->_compileResultDirectory;
	} // end getCompileResultDirectory();

	/**
	 * Returns translation for specified group and id.
	 * 
	 * @param string $language The language
	 * @param string $group The message group
	 * @param string $id The message identifier
	 * @return string|null
	 */
	public function getMessage($language, $group, $id)
	{
		if(isset($this->_assigned[$group]->$id))
		{
			return $this->_assigned[$group]->$id;
		}
		if(isset($this->_messages[$group]->$id))
		{
			return $this->_messages[$group]->$id;
		}
		return null;
	} // end getMessage();

	/**
	 * Assings the data to the specified message. The method must return
	 * true, if the assignment was successful and false otherwise.
	 *
	 * @param string $language The language
	 * @param string $group The message group
	 * @param string $id The message identifier
	 * @param array $data The data to assign
	 * @return boolean
	 */
	public function assign($language, $group, $id, array $data)
	{
		if($this->_assigned === null)
		{
			if($this->_messages !== null)
			{
				$this->_assigned = $this->_messages;
			}
			else
			{
				return false;
			}
		}
		if(isset($this->_messsages[$group]->$id))
		{
			$this->_assigned[$group]->$id = vsprintf($this->_messages[$group]->$id, $data);
			return true;
		}
		return false;
	} // end assign();

	/**
	 * Loads a group of messages in the specified language.
	 *
	 * @param string $language The language name.
	 * @param string $group Group name
	 * @return boolean
	 */
	public function loadGroupLanguage($language, $group)
	{
$empty = <<<XML
<?xml version='1.0' standalone='yes'?>
 <data>
 </data>
XML;
		if($this->getFileExistsCheck() && !file_exists($this->getDirectory().$language.DIRECTORY_SEPARATOR.$group.'.xml'))
		{
			throw new Opc_Translate_Adapter_Exception('Translation file for group "'.$group.'" is not found for language "'.$language.'"!');
			return false;
		}
		if($this->getCompileResult())
		{
			$compileTime = @filemtime($this->getCompileResultDirectory().$language.'_'.$group.'.xml.php');
			$fileTime = @filemtime($this->getDirectory().$language.DIRECTORY_SEPARATOR.$group.'.xml');
			if($compileTime !== false && $compileTime > $fileTime &&
				($contents = file_get_contents($this->getCompileResultDirectory().$language.'_'.$group.'.xml.php')) &&
				(isset($options['recompile'])?$options['recompile']:true)
			){
				$data = unserialize($contents);
			}
			else
			{
				// We need to compile retreived data
				$data = @simplexml_load_file($this->getDirectory().$language.DIRECTORY_SEPARATOR.$group.'.xml');
				if(file_put_contents($this->getCompileResultDirectory().$language.'_'.$group.'.xml.php', serialize($data))	=== false)
				{
					// Error writing file
					throw new Opc_Translate_Adapter_Exception('File "'.$language.DIRECTORY_SEPARATOR.$group.'.xml" could not be written in cache.');
					return false;
				}
			}
		}
		else
		{
			$data = @simplexml_load_file($this->getDirectory().$language.DIRECTORY_SEPARATOR.$group.'.xml');
		}
		// Copying data.
		if($this->_messages[$group] === null)
		{
			$this->_messages[$group] = new SimpleXMLElement($empty);
		}
		foreach($data as $key => $value)
		{
			$this->_messages[$group]->$key = $value;
		}
		return true;
	} // end loadGroupLanguage();

	/**
	 * Loads new language.
	 *
	 * @param string $language New language
	 */
	public function loadLanguage($language)
	{
		if($this->getFileExistsCheck() && !file_exists($this->getDirectory().$language.'.xml'))
		{
			throw new Opc_Translate_Adapter_Exception('Translation file is not found for language "'.$language.'"!');
			return false;
		}
		if($this->getCompileResult())
		{
			$compileTime = @filemtime($this->getCompileResultDirectory().$language.'.xml.php');
			$fileTime = @filemtime($this->getDirectory().$language.'.xml');
			if($compileTime !== false && $compileTime > $fileTime &&
				($contents = file_get_contents($this->getCompileResultDirectory().$language.'.xml.php')) &&
				(isset($options['recompile'])?$options['recompile']:true)
			){
				$data = unserialize($contents);
			}
			else
			{
				// We need to compile retreived data
				$data = @simplexml_load_file($this->getDirectory().$language.'.xml');
				if(file_put_contents($this->getCompileResultDirectory().$language.'.xml.php', serialize($data))	=== false)
				{
					// Error writing file
					throw new Opc_Translate_Adapter_Exception('File "'.$language.'.xml" could not be written in cache.');
					return false;
				}
			}
		}
		else
		{
			$data = @simplexml_load_file($this->getDirectory().$language.'.xml');
		}
		// Copying data.
		foreach($data as $key => $value)
		{
			$this->_messages[$key] = $value;
		}
		unset($data);
		return true;
	} // end loadLanguage();
} // end Xml;
