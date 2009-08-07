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
 * The class represents a translation interface for Open Power Template 2.0
 *
 * @author Amadeusz "megawebmaster" Starzykiewicz
 */
class Opc_View_Translation implements Opl_Translation_Interface
{
	private
		/**
		 * It contains default language messages.
		 * @var Array
		 */
		$_default = null,
		/**
		 * It contains selected language messages.
		 * @var Array
		 */
		$_translation = null,
		/**
		 * Variable contains translation directory.
		 * @var String
		 */
		$_translationsDirectory = null,
		/**
		 * Variable contains actually loaded language identifier.
		 * @var String
		 */
		$_actualLanguage = null,
		/**
		 * Variable tells if we want to check if file exists.
		 * @var Boolean
		 */
		$_fileCheck = false,
		/**
		 * Variable contains string which will be returned when there is no
		 * translation for an element.
		 * @var String
		 */
		$_unknownMessage = '',
		/**
		 * Default language to use in case when there is no language selected
		 * or selected language has not specified required translation.
		 * @var String
		 */
		$_defaultLanguage = 'en';

	/**
	 * Returns translation for specified group and id.
	 * @param String|Integer $group Group name
	 * @param String|Integer $id Id
	 * @return String
	 */
	public function _($group, $id)
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
		return $this->_unknownMessage;
	} // end _();

	public function assign($group, $id)
	{
		// TODO: Implement this
		// null
	} // end assign();

	private function _loadLanguage($lang, $type = 'translation')
	{
		// TODO: Make loader for translation files.
		if($this->_fileCheck)
		{
			// Check if file exists
		}
		if($type == 'translation')
		{
			$this->_translation = array();
			// Load file for translation
		}
		elseif($type == 'default')
		{
			$this->_default = array();
			// Load default translation file
		}
		else
		{
			return false;
		}
		return true;
	} // end _loadLanguage();

	/**
	 * Function chooses new language for messages.
	 *
	 * Returns true if there is language file in translations directory and function
	 * is able to load it, otherwise it returns false and uses default language.
	 * 
	 * @param String $lang New language
	 * @return Boolean
	 */
	public function setLanguage($lang)
	{
		if($this->_translationsDirectory !== null)
		{
			if($this->_translation === null)
			{
				return $this->_loadLanguage($lang);
			}
			elseif($this->_actualLanguage != $lang)
			{
				return $this->_loadLanguage($lang);
			}
		}
		else
		{
			throw new Opc_View_TranslationNotSetTranslationDirectory_Exception();
		}
		return true;
	} // end setLanguage();
	
	/**
	 * Gives access to control default language.
	 * @param String $lang New default language
	 */
	public function setDefaultLanguage($lang)
	{
		$this->_defaultLanguage = $lang;
	} // end setDefaultLanguage();

	/**
	 * Sets directory, where translation files are.
	 * @param String $dir New directory
	 */
	public function setLanguageDirectory($dir)
	{
		if($dir[strlen($dir)-1] != DIRECTORY_SEPARATOR)
		{
			$dir .= DIRECTORY_SEPARATOR;
		}

		// Prevention against current directory changes in Apache
		// which affects destructors. We avoid it by switching to the
		// absolute path.

		if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false)
		{
			$dir = realpath($dir).DIRECTORY_SEPARATOR;
		}
		$this->_translationsDirectory = $dir;
	} // end setLanguageDirectory();

	/**
	 * Sets file existence checking.
	 * @param Boolean $state New state
	 */
	public function setFileCheck($state)
	{
		$this->_fileCheck = (boolean)$state;
	} // end setFileCheck();

	/**
	 * Sets message which is returned when there is no translation.
	 * @param String $msg Message
	 */
	public function setUnknownMessage($msg)
	{
		$this->_unknownMessage = $msg;
	} // end setUnknownMessage();
} // end Opc_View_Translation;