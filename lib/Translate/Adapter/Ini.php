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
 * The translation adapter for INI files representing the message format.
 *
 * @author Tomasz Jędrzejewski <http://www.zyxist.com>
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Opc_Translate_Adapter_Ini extends Opc_Translate_Adapter
{
	protected
		/**
		 * Language files directory.
		 * @var string
		 */
		$_directory = null,
		/**
		 * Loaded translation.
		 * @var array
		 */
		$_translation = null,
		/**
		 * Loaded default translation.
		 * @var array
		 */
		$_default = null,
		/**
		 * Assigned from template values.
		 * @var array
		 */
		$_assigned = null,
		/**
		 * File existence checking state.
		 * @var boolean
		 */
		$_fileCheck = false;

	/**
	 * Creates the adapter and applies the options.
	 * 
	 * @param array $options The adapter options.
	 */
	public function __construct(array $options)
	{
		if(!empty($options['directory']))
		{
			if($options['directory'][strlen($options['directory'])-1] != DIRECTORY_SEPARATOR)
			{
				$options['directory'] .= DIRECTORY_SEPARATOR;
			}
			$this->_directory = $options['directory'];
		}
		if(!empty($options['fileCheck']))
		{
			$this->_fileCheck = (boolean)$options['fileCheck'];
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
	 * Returns the current directory.
	 * 
	 * @return string
	 */
	public function getDirectory()
	{
		return $this->_directory;
	} // end getDirectory();

	/**
	 * Returns translation for specified group and id.
	 * 
	 * @param string $language The language
	 * @param string $group The message group
	 * @param string $msg The message identifier
	 * @return string|null
	 */
	public function getMessage($language, $group, $id)
	{
		if(isset($this->_assigned[$group][$id]))
		{
			return $this->_assigned[$group][$id];
		}
		if(isset($this->_translation[$group][$id]))
		{
			return $this->_translation[$group][$id];
		}
		if(isset($this->_default[$group][$id]))
		{
			return $this->_default[$group][$id];
		}
		return null;
	} // end getMessage();

	/**
	 * Assings the data to the specified message.
	 *
	 * @param string $group The message group
	 * @param string $id The message identifier
	 * @param array $data The data to assign.
	 */
	public function assign($group, $id, $data)
	{
		if(isset($this->_translation[$group][$id]))
		{
			$this->_assigned[$group][$id] = vsprintf($this->_translation[$group][$id], $data);
		}
		elseif(isset($this->_default[$group][$id]))
		{
			$this->_assigned[$group][$id] = vsprintf($this->_default[$group][$id], $data);
		}
		else
		{
			throw new Opc_TranslateCannotAssignData_Exception($group, $id);
		}
	} // end assign();

	/**
	 * Loads a group of messages in the specified language.
	 *
	 * @param string $group Group name
	 * @param string $language The language name.
	 * @param string $type Type of translation
	 * @return boolean
	 */
	public function loadGroupLanguage($group, $language, $type = 'translation')
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
		switch($type)
		{
			case 'translation':
				$this->_translation[$group] = $data;
				break;
			case 'default':
				$this->_default[$group] = $data;
				break;
		}
		return true;
	} // end loadGroupLanguage();


	public function setFileCheck($state)
	{
		$this->_fileCheck = (boolean)$state;
	} // end setFileCheck();
} // end Opc_Translate_Adapter_Ini;
