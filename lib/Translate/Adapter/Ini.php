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
 * $Id: Cache.php 189 2009-08-07 12:44:37Z megawebmaster $
 */

/**
 * The translation adapter representing the message format.
 * Needs to be completed.
 *
 * @author Tomasz Jędrzejewski <http://www.zyxist.com>
 */
class Opc_Translate_Adapter_Ini extends Opc_Translate_Adapter
{
	/**
	 * The language directory.
	 * @var string
	 */
	protected $_directory = null;

	/**
	 * Creates the adapter and applies the options.
	 * @param Array $options The adapter options.
	 */
	public function __construct($options)
	{
		if(!empty($options['directory']))
		{
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
	 * @param String $language The language
	 * @param String $group The message group
	 * @param String $msg The message identifier
	 * @return String
	 */
	public function getMessage($language, $group, $id)
	{
		if(isset($this->_translation[$group][$id]))
		{
			return $this->_translation[$group][$id];
		}
		if(isset($this->_default[$group][$id]))
		{
			return $this->_default[$group][$id];
		}
		else
		{
			if($this->_loadLanguage($this->_defaultLanguage, 'default'))
			{
				if(isset($this->_default[$group][$id]))
				{
					return $this->_default[$group][$id];
				}
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

	} // end assign();

	/**
	 * Loads a group of messages in the specified language.
	 *
	 * @param String $language The language name.
	 * @param String $groupName The group name
	 */
	protected function _loadGroup($groupName)
	{

	} // end _loadGroup();
} // end Opc_Translate_Adapter_Ini;