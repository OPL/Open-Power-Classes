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
 * $Id: Xml.php 201 2010-03-03 21:56:16Z megawebmaster $
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
		 * File existence checking state.
		 * @var boolean
		 */
		$_fileExistsCheck = false,
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
	public function __construct(array $options)
	{
		if(!empty($options['directory']))
		{
			$this->setDirectory($options['directory']);
		}
		if(!empty($options['fileExistsCheck']))
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
	 * Returns the current directory.
	 * 
	 * @return string
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
		if(isset($this->_messages->$group->$id))
		{
			return $this->_messages->$group->$id;
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
				throw new Opc_Translate_Adapter_Xml_NoTranslationLoaded_Exception();
			}
		}
		if(isset($this->_messsages->$group->$id))
		{
			$this->_assigned->$group->$id = vsprintf($this->_messages->$group->$id, $data);
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

		$data = @simplexml_load_file($this->_directory.$language.DIRECTORY_SEPARATOR.$group.'.xml');
		if($data === false)
		{
			if($this->_fileExistsCheck)
			{
				throw new Opc_Translate_Adapter_GroupFileNotFound_Exception($group, $language);
			}
			else
			{
				return false;
			}
		}
		if($this->_messages === null)
		{
			$this->_messages = new SimpleXMLElement($empty);
		}
		foreach($data as $key => $value)
		{
			$this->_messages->$group->$key = $value;
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
$empty = <<<XML
<?xml version='1.0' standalone='yes'?>
 <data>
 </data>
XML;

		$data = @simplexml_load_file($this->_directory.$language.'.xml');
		if($data === false)
		{
			if($this->_fileExistsCheck)
			{
				throw new Opc_Translate_Adapter_FileNotFound_Exception($language);
			}
			else
			{
				return false;
			}
		}
		if($this->_messages === null)
		{
			$this->_messages = new SimpleXMLElement($empty);
		}
		foreach($data as $key => $value)
		{
			$this->_messages->$key = $value;
		}
		return true;
	} // end loadLanguage();
} // end Opc_Translate_Adapter_Xml;
