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
 */

/**
 * The translation adapter for INI files representing the message format.
 * Needs to be completed.
 *
 * @author Tomasz Jędrzejewski <http://www.zyxist.com>
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 */
class Opc_Translate_Adapter_Ini extends Opc_Translate_Adapter
{
	protected
		/**
		 * Language files directory.
		 * @var String
		 */
		$_directory = null,
		/**
		 * Loaded translation.
		 * @var Array
		 */
		$_translation = null,
		/**
		 * Loaded default translation.
		 * @var Array
		 */
		$_default = null,
		/**
		 * Current loaded language.
		 * @var String
		 */
		$_current = null,
		/**
		 * Another language set groups.
		 * @var Array
		 */
		$_groups = array(),
		/**
		 * File existence checking state.
		 * @var Boolean
		 */
		$_fileCheck = false;

	/**
	 * Creates the adapter and applies the options.
	 * @param Array $options The adapter options.
	 */
	public function __construct(Array $options)
	{
		if(!empty($options['directory']))
		{
			if($options['directory'][strlen($options['directory'])-1] != DIRECTORY_SEPARATOR)
			{
				$options['directory'] .= DIRECTORY_SEPARATOR;
			}
			$this->_directory = $options['directory'];
		}
	} // end __construct();

	/**
	 * Sets the message directory.
	 * @param String $directory The new directory.
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
	 * Returns the current directory.
	 * @return String
	 */
	public function getDirectory()
	{
		return $this->_directory;
	} // end getDirectory();

	/**
	 * Returns translation for specified group and id.
	 * 
	 * @param String $language The language
	 * @param String $group The message group
	 * @param String $msg The message identifier
	 * @return String|Null
	 */
	public function getMessage($language, $group, $id, $type='translation')
	{
		if($type == 'translation')
		{
			if($language != $this->_current)
			{
				$this->_loadLanguage($language,$type);
			}
			if(isset($this->_translation[$group][$id]))
			{
				return $this->_translation[$group][$id];
			}
		}
		elseif($type == 'default')
		{
			if($this->_default === null)
			{
				$this->_loadLanguage($language,$type);
			}
			if(isset($this->_default[$group][$id]))
			{
				return $this->_default[$group][$id];
			}
		}
		return null;
	} // end getMessage();

	/**
	 * Assings the data to the specified message.
	 *
	 * @param String $language The language
	 * @param String $group The message group
	 * @param String $msg The message identifier
	 * @param ... The data to assign.
	 */
	public function assign($language, $group, $msg)
	{
		// TODO: Implement
	} // end assign();

	/**
	 * Loads a group of messages in the specified language.
	 *
	 * @param String $language The language name.
	 * @param String $group The group name
	 */
	protected function _loadGroup($language, $group)
	{
		$data = @parse_ini_file($this->_directory.$language.DIRECTORY_SEPARATOR.$group.'.ini');
		if($data === false)
		{
			if($this->_fileCheck)
			{
				throw new Opc_TranslateGroupFileNotFound_Exception($group, $language);
			}
			else
			{
				return false;
			}
		}
		$this->_translation[$group] = $data;
		return true;
	} // end _loadGroup();

	/**
	 * Loads language file for whole translation.
	 *
	 * @param String $language Language to be loaded
	 * @param String $type Type of loaded language
	 * @return Boolean
	 */
	protected function _loadLanguage($language, $type)
	{
		$data = @parse_ini_file($this->_directory.$language.'.ini',true);
		if($data === false)
		{
			if($this->_fileCheck)
			{
				throw new Opc_TranslateFileNotFound_Exception($language, $type);
			}
			else
			{
				return false;
			}
		}
		switch($type)
		{
			case 'translation':
				$this->_translation = $data;
				break;
			case 'default':
				$this->_default = $data;
				break;
		}
		return true;
	} // end _loadLanguage();

	/**
	 * Sets the new language for whole translation.
	 *
	 * @param String $language Language to load
	 * @return Boolean
	 */
	public function setLanguage($language)
	{
		if($this->_directory !== null)
		{
			if($this->_translation === null)
			{
				return $this->_loadLanguage($language, 'translation');
			}
			elseif($this->_current != $language)
			{
				return $this->_loadLanguage($language, 'translation');
			}
		}
		else
		{
			throw new Opc_TranslateNotSetTranslationDirectory_Exception();
		}
		return true;
	} // end setLanguage();

	/**
	 * Sets the new language for specified group.
	 *
	 * @param String $group Group name
	 * @param String $language New language
	 * @return Boolean
	 */
	public function setGroupLanguage($group, $language)
	{
		if($this->_directory !== null)
		{
			if(!isset($this->_groups[$group]))
			{
				return $this->_loadGroup($group, $language);
			}
			elseif($this->_groups[$group] != $language)
			{
				$this->_translation[$group] = array();
				return $this->_loadGroup($group, $language);
			}
		}
		else
		{
			throw new Opc_TranslateNotSetTranslationDirectory_Exception();
		}
		return true;
	} // end setGroupLanguage();

	public function setFileCheck($state)
	{
		$this->_fileCheck = (boolean)$state;
	} // end setFileCheck();
} // end Opc_Translate_Adapter_Ini;