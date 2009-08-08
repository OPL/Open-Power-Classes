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
 * The class represents a translation interface for OPL
 *
 * @author Amadeusz "megawebmaster" Starzykiewicz
 * @author Tomasz "Zyx" Jędrzejewski <http://www.zyxist.com>
 */
class Opc_Translate implements Opl_Translation_Interface
{
	protected
		/**
		 * The default adapter.
		 * @var Opc_Translate_Adapter
		 */
		$_defaultAdapter = null,
		/**
		 * The group adapters.
		 * @var Array
		 */
		$_groupAdapters = array(),
		/**
		 * Variable contains the currently loaded language identifier.
		 * @var String
		 */
		$_currentLanguage = null,
		/**
		 * Default language to use in case when there is no language selected
		 * or selected language has not specified required translation.
		 * @var String
		 */
		$_defaultLanguage = 'en';

	/**
	 * Creates the new translation object.
	 * @param Opc_Translate_Adapter $adapter The default translation adapter.
	 */
	public function __construct(Opc_Translate_Adapter $adapter)
	{
		$this->setAdapter($adapter);
	} // end __construct();

	/**
	 * Sets the default translation adapter.
	 * @param Opc_Translate_Adapter $adapter The new default adapter.
	 * @return Opc_Translate
	 */
	public function setAdapter(Opc_Translate_Adapter $adapter)
	{
		$this->_defaultAdapter = $adapter;
		return $this;
	} // end setAdapter();

	/**
	 * Returns the current default adapter.
	 * @return Opc_Translate_Adapter
	 */
	public function getAdapter()
	{
		return $this->_defaultAdapter;
	} // end getAdapter();

	/**
	 * Sets the translation adapter for the specified message group.
	 * @param String $group The group name.
	 * @param Opc_Translate_Adapter $adapter The new default adapter.
	 * @return Opc_Translate
	 */
	public function setGroupAdapter($group, Opc_Translate_Adapter $adapter)
	{
		$this->_groupAdapters[(string)$group] = $adapter;
		return $this;
	} // end setAdapter();

	/**
	 * Returns the group adapter. If the group does not have
	 * any adapter set, it returns the default adapter.
	 *
	 * @param String $group The group name.
	 * @return Opc_Translate_Adapter
	 */
	public function getGroupAdapter($group)
	{
		if(isset($this->_groupAdapters[$group]))
		{
			return $this->_groupAdapters[$group];
		}
		return $this->_defaultAdapter;
	} // end getAdapter();

	/**
	 * Returns translation for specified group and id.
	 * @param String|Integer $group Group name
	 * @param String|Integer $id Id
	 * @return String
	 */
	public function _($group, $id)
	{
		// Select the adapter.
		if(isset($this->_groupAdapters[$group]))
		{
			$adapter = $this->_groupAdapters[$group];
		}
		else
		{
			$adapter = $this->_defaultAdapter;
		}

		// Try to select the message.
		if(($msg = $adapter->getMessage($this->_currentLanguage, $group, $id)) !== null)
		{
			return $msg;
		}
		if(($msg = $adapter->getMessage($this->_defaultLanguage, $group, $id)) !== null)
		{
			return $msg;
		}
		throw new Opc_MessageNotFound_Exception($group, $id);
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
		// TODO: There are no directories here, it must be removed.
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
} // end Opc_View_Translation;