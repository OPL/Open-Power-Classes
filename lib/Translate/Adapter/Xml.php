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
 * $Id: Ini.php 201 2009-08-09 19:19:16Z extremo $
 */

/**
 * The translation adapter for XML files representing the message format.
 * Needs to be completed.
 *
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Opc_Translate_Adapter_Xml extends Opc_Translate_Adapter
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
		if(isset($this->_assigned->$group->$id))
		{
			return $this->_assigned->$group->$id;
		}
		if(isset($this->_translation->$group->$id))
		{
			return $this->_translation->$group->$id;
		}
		if(isset($this->_default->$group->$id))
		{
			return $this->_default->$group->$id;
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
		if($this->_assigned === null)
		{
			if($this->_translation !== null)
			{
				$this->_assigned = $this->_translation;
			}
			elseif($this->_default !== null)
			{
				$this->_assigned = $this->_default;
			}
			else
			{
				throw new Opc_TranslateNotLoadedTranslation_Exception();
			}
		}
		if(isset($this->_translation->$group->$id))
		{
			$this->_assigned->$group->$id = vsprintf($this->_translation->$group->$id, $data);
		}
		elseif(isset($this->_default->$group->$id))
		{
			$this->_assigned->$group->$id = vsprintf($this->_default->$group->$id, $data);
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
$empty = <<<XML
<?xml version='1.0' standalone='yes'?>
 <data>
 </data>
XML;

		$data = @simplexml_load_file($this->_directory.$language.DIRECTORY_SEPARATOR.$group.'.xml');
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
				if($this->_translation === null)
				{
					$this->_translation = new SimpleXMLElement($empty);
				}
				foreach($data as $key => $value)
				{
					$this->_translation->$group->$key = $value;
				}
				break;
			case 'default':
				if($this->_default === null)
				{
					$this->_default = new SimpleXMLElement($empty);
				}
				foreach($data as $key => $value)
				{
					$this->_default->$group->$key = $value;
				}
				break;
		}
		return true;
	} // end loadGroupLanguage();

	public function setFileCheck($state)
	{
		$this->_fileCheck = (boolean)$state;
	} // end setFileCheck();
} // end Opc_Translate_Adapter_Ini;
