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
 * $Id: Yaml.php 201 2010-03-03 21:52:16Z megawebmaster $
 */

/**
 * The translation adapter for YAML files representing the message format.
 *
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 */
// TODO: Add getting initial configuration from Opc_Class
class Opc_Translate_Adapter_Yaml implements Opc_Translate_Adapter
{
	protected
		/**
		 * Directory of files.
		 * @var string $_directory
		 */
		$_directory = null,
		/**
		 * We need to check files existence?
		 * @var boolean $_fileExistsCheck
		 */
		$_fileExistsCheck = false,
		/**
		 * We want to compile result data?
		 * @var boolean $_compileResult
		 */
		$_compileResult = true,
		/**
		 * Contains parser object
		 * @var Opc_Translate_Adapter_Yaml_sfYamlParser $_parser
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
		return $this->_compileResult;
	} // end getFileExistsCheck();

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
			$this->_parser = new Opc_Translate_Adapter_Yaml_sfYamlParser();
		}
		if($this->_fileExistsCheck)
		{
			if(!file_exists($this->_directory.$language.'.yml'))
			{
				throw new Opc_Translate_Adapter_FileNotFound_Exception($language);
				return false;
			}
		}
		try
		{
			if($this->_compileResult)
			{
				$compileTime = @filemtime($this->_directory.'cache'.DIRECTORY_SEPARATOR.$language.'.yml.php');
				$fileTime = @filemtime($this->_directory.$language.'.yml');
				if($compileTime !== false && $compileTime > $fileTime &&
					($contents = file_get_contents($this->_directory.'cache'.DIRECTORY_SEPARATOR.$language.'.yml.php')) &&
					(isset($options['recompile'])?$options['recompile']:true)
				){
					$data = unserialize($contents);
				}
				else
				{
					// We need to compile retreived data
					$data = $this->_parser->parse(file_get_contents($this->_directory.$language.'.yml'));
					if(file_put_contents(
							$this->_directory.'cache'.DIRECTORY_SEPARATOR.$language.'.yml.php', serialize($data))
						=== false)
					{
						// Error writing file
						throw new Opc_Translate_Adapter_Yaml_CompileWriteFile_Exception($language, 'yml');
						return false;
					}
				}
			}
			else
			{
				$data = $this->_parser->parse(file_get_contents($this->_directory.$language.'.yml'));
			}
			$this->_messages = $data;
			return true;
		}
		catch(InvalidArgumentException $e)
		{
			throw new Opc_Translate_Adapter_YamlParser_Exception($language, $e->getMessage());
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
			$this->_parser = new Opc_Translate_Adapter_Yaml_sfYamlParser();
		}
		if($this->_fileExistsCheck)
		{
			if(!file_exists($this->_directory.$language.DIRECTORY_SEPARATOR.$group.'.yml'))
			{
				throw new Opc_Translate_Adapter_FileNotFound_Exception($language, $type);
				return false;
			}
		}
		try
		{
			if($this->_compileResult)
			{
				$compileTime = @filemtime($this->_directory.'cache'.DIRECTORY_SEPARATOR.$language.'.'.$group.'.yml.php');
				$fileTime = @filemtime($this->_directory.$language.DIRECTORY_SEPARATOR.$group.'.yml');
				if($compileTime !== false && $compileTime > $fileTime &&
					($contents = file_get_contents($this->_directory.'cache'.DIRECTORY_SEPARATOR.$file.'.yml.php')) &&
					(isset($options['recompile'])?$options['recompile']:true)
				){
					$data = unserialize($contents);
				}
				else
				{
					// We need to compile retreived data
					$data = $this->_parser->parse(file_get_contents($this->_directory.$language.DIRECTORY_SEPARATOR.$group.'.yml'));
					if(file_put_contents(
							$this->_directory.'cache'.DIRECTORY_SEPARATOR.$language.'.yml.php', serialize($data))
						=== false)
					{
						// Error writing file
						throw new Opc_Translate_Adapter_Yaml_CompileWriteFile_Exception($language.'.'.$group, 'yml');
						return false;
					}
				}
			}
			else
			{
				$data = $this->_parser->parse(file_get_contents($this->_directory.$language.DIRECTORY_SEPARATOR.$group.'.yml'));
			}
			$this->_messages[$group] = $data;
			return true;
		}
		catch(InvalidArgumentException $e)
		{
			throw new Opc_Translate_Adapter_YamlParser_Exception($language.'.'.$group, $e->getMessage());
			return false;
		}
	} // end loadGroupLanguage();
} // end Opc_Translate_Adapter_Yaml;