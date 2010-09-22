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
use Symfony\Component\Yaml\Parser;
use \Opl_Registry;
use \InvalidArgumentException;
/**
 * The translation adapter for YAML files representing the message format.
 *
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 */
class Yaml implements Adapter
{
	protected
		/**
		 * Opc\Core instance
		 * @var Opc\Core
		 */
		$_opc = null,
		/**
		 * Directory of files.
		 * @var string $_directory
		 */
		$_directory = null,
		/**
		 * We need to check files existence?
		 * @var boolean $_fileExistsCheck
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
		 * Contains parser object
		 * @var Symfony\Component\Yaml\Parser $_parser
		 */
		$_parser = null,
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
	 * Configures adapter with provided options.
	 * @param array $options Options
	 */
	public function __construct(array $options = array())
	{
		if(!Opl_Registry::exists('opc'))
		{
			throw new Opc_Translate_Adapter_Exception('Opc\Core class not exists!');
		}
		$this->_opc = Opl_Registry::get('opc');
		if(isset($options['fileExistsCheck']))
		{
			$this->setFileExistsCheck($options['fileExistsCheck']);
		}
		if(isset($options['directory']))
		{
			$this->setDirectory($options['directory']);
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
	 * Sets files directory.
	 * @param string $directory Directory
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
				$this->_compileResultDirectory = $this->getDirectory().substr($this->_opc->translateCompileResultDirectory, 1).DIRECTORY_SEPARATOR;
			}
			else
			{
				$this->_compileResultDirectory = $this->_opc->translateCompileResultDirectory;
			}
		}
		return $this->_compileResultDirectory;
	} // end getCompileResultDirectory();

	/**
	 * Returns the message in the specified language.
	 *
	 * @param string $language The language
	 * @param string $group The message group
	 * @param string $id The message identifier
	 * @return string
	 */
	public function getMessage($language, $group, $id)
	{
		if(isset($this->_assigned[$group][$id]))
		{
			return $this->_assigned[$group][$id];
		}
		if(isset($this->_messages[$group][$id]))
		{
			return $this->_messages[$group][$id];
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
		if(isset($this->_messages[$group][$id]))
		{
			$this->_assigned[$group][$id] = vsprintf($this->_messages[$group][$id], $data);
			return true;
		}
		return false;
	} // end assign();

	/**
	 * Loads new language.
	 *
	 * @param string $language New language
	 */
	public function loadLanguage($language)
	{
		if($this->_parser === null)
		{
			$this->_parser = new Parser();
		}
		if($this->getFileExistsCheck() && !file_exists($this->getDirectory().$language.'.yml'))
		{
			throw new Opc_Translate_Adapter_Exception('Translation file is not found for language "'.$language.'"!');
			return false;
		}
		try
		{
			if($this->getCompileResult())
			{
				$compileTime = @filemtime($this->getCompileResultDirectory().$language.'.yml.php');
				$fileTime = @filemtime($this->getDirectory().$language.'.yml');
				if($compileTime !== false && $compileTime > $fileTime &&
					($contents = file_get_contents($this->getCompileResultDirectory().$language.'.yml.php')) &&
					(isset($options['recompile'])?$options['recompile']:true)
				){
					$data = unserialize($contents);
				}
				else
				{
					// We need to compile retreived data
					$data = $this->_parser->parse(file_get_contents($this->getDirectory().$language.'.yml'));
					if(file_put_contents($this->getCompileResultDirectory().$language.'.yml.php', serialize($data))	=== false)
					{
						// Error writing file
						throw new Opc_Translate_Adapter_Exception('File "'.$language.'.yml" could not be written in cache.');
						return false;
					}
				}
			}
			else
			{
				$data = $this->_parser->parse(file_get_contents($this->getDirectory().$language.'.yml'));
			}
			$this->_messages = $data;
			return true;
		}
		catch(InvalidArgumentException $e)
		{
			throw new Opc_Translate_Adapter_Exception('There is an error in file "'.$language.'.yml". Returned error message: "'.$e->getMessage().'"');
			return false;
		}
	} // end loadLanguage();

	/**
	 * Loads new language for specified group.
	 *
	 * @param string $language New language
	 * @param string $group Group name
	 */
	public function loadGroupLanguage($language, $group)
	{
		if($this->_parser === null)
		{
			$this->_parser = new Parser();
		}
		if($this->getFileExistsCheck() && !file_exists($this->getDirectory().$language.DIRECTORY_SEPARATOR.$group.'.yml'))
		{
			throw new Opc_Translate_Adapter_Exception('Translation file for group "'.$group.'" is not found for language "'.$language.'"!');
			return false;
		}
		try
		{
			if($this->getCompileResult())
			{
				$compileTime = @filemtime($this->getCompileResultDirectory().$language.'_'.$group.'.yml.php');
				$fileTime = @filemtime($this->getDirectory().$language.DIRECTORY_SEPARATOR.$group.'.yml');
				if($compileTime !== false && $compileTime > $fileTime &&
					($contents = file_get_contents($this->getCompileResultDirectory().$language.'_'.$group.'.yml.php')) &&
					(isset($options['recompile'])?$options['recompile']:true)
				){
					$data = unserialize($contents);
				}
				else
				{
					// We need to compile retreived data
					$data = $this->_parser->parse(
						file_get_contents($this->getDirectory().$language.DIRECTORY_SEPARATOR.$group.'.yml')
					);
					if(file_put_contents($this->getCompileResultDirectory().$language.'_'.$group.'.yml.php', serialize($data))	=== false)
					{
						// Error writing file
						throw new Opc_Translate_Adapter_Exception('File "'.$language.DIRECTORY_SEPARATOR.$group.'.yml" could not be written in cache.');
						return false;
					}
				}
			}
			else
			{
				$data = $this->_parser->parse(file_get_contents($this->getDirectory().$language.DIRECTORY_SEPARATOR.$group.'.yml'));
			}
			$this->_messages[$group] = $data;
			return true;
		}
		catch(InvalidArgumentException $e)
		{
			throw new Opc_Translate_Adapter_Exception('There is an error in group "'.$group.'" of language "'.$language.'". Returned error message: "'.$e->getMessage().'"');
			return false;
		}
	} // end loadGroupLanguage();
} // end Yaml;