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
 * The translation adapter for INI files representing the message format.
 *
 * @author Tomasz Jędrzejewski <http://www.zyxist.com>
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
// TODO: Add getting initial configuration from Opc\Core
class Ini implements Adapter
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
		 * Loaded translation.
		 * @var array
		 */
		$_messages = null,
		/**
		 * Assigned from template values.
		 * @var array
		 */
		$_assigned = null,
		/**
		 * File existence checking state.
		 * @var boolean
		 */
		$_fileExistsCheck = false;

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
	} // end __construct();

	/**
	 * Sets the message directory.
	 * 
	 * @param string $directory The new directory.
	 */
	public function setDirectory($directory)
	{
		if($directory[strlen($directory)-1] != DIRECTORY_SEPARATOR)
		{
			$directory .= DIRECTORY_SEPARATOR;
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
	 * Sets file checking on.
	 *
	 * Class checks if file exists before loading it.
	 *
	 * @param boolean $state State of feature
	 */
	public function setFileExistsCheck($state)
	{
		$this->_fileExistsCheck = (boolean)$state;
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
	 * Returns translation for specified group and id.
	 * 
	 * @param string $language The language
	 * @param string $group The message group
	 * @param string $id The message identifier
	 * @return string|null
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
			$this->_assigned[$group][$id] = vsprintf($this->_translation[$group][$id], $data);
			return true;
		}
		return false;
	} // end assign();

	/**
	 * Loads a group of messages in the specified language.
	 *
	 * @param string $language The language name.
	 * @param string $group Group name.
	 * @return boolean
	 */
	public function loadGroupLanguage($language, $group)
	{
		$data = @parse_ini_file($this->getDirectory().$language.DIRECTORY_SEPARATOR.$group.'.ini');
		if($data === false)
		{
			if($this->getFileExistsCheck())
			{
				throw new Opc_Translate_Adapter_Exception('Translation file for group "'.$group.'" is not found for language "'.$language.'"!');
			}
			else
			{
				return false;
			}
		}
		$this->_messages[$group] = $data;
		return true;
	} // end loadGroupLanguage();

	/**
	 * Loads language file for whole translation.
	 *
	 * @param string $language Language to be loaded
	 * @return boolean
	 */
	public function loadLanguage($language)
	{
		$data = @parse_ini_file($this->getDirectory().$language.'.ini',true);
		if($data === false)
		{
			if($this->getFileExistsCheck())
			{
				throw new Opc_Translate_Adapter_Exception('Translation file is not found for language "'.$language.'"!');
			}
			else
			{
				return false;
			}
		}
		$this->_messages = $data;
		return true;
	} // end loadLanguage();
} // end Ini;
