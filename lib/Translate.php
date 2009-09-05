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
 * The class represents a translation interface for OPL
 *
 * @author Amadeusz "megawebmaster" Starzykiewicz
 * @author Tomasz "Zyx" Jędrzejewski <http://www.zyxist.com>
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
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
		 * @var array
		 */
		$_groupAdapters = array(),
		/**
		 * Variable contains the currently loaded language identifier.
		 * @var string
		 */
		$_currentLanguage = null,
		/**
		 * Array contains groups and its languages when they are other than main.
		 * @var array
		 */
		$_groupLanguages = array(),
		/**
		 * Default language to use in case when there is no language selected
		 * or selected language has not specified required translation.
		 * @var string
		 */
		$_defaultLanguage = 'en';

	/**
	 * Creates the new translation object.
	 * 
	 * @param Opc_Translate_Adapter $adapter The default translation adapter.
	 */
	public function __construct(Opc_Translate_Adapter $adapter)
	{
		$this->setAdapter($adapter);
	} // end __construct();

	/**
	 * Sets the default translation adapter.
	 * 
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
	 * 
	 * @return Opc_Translate_Adapter
	 */
	public function getAdapter()
	{
		return $this->_defaultAdapter;
	} // end getAdapter();

	/**
	 * Sets the translation adapter for the specified message group.
	 * 
	 * @param string $group The group name.
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
	 * @param string $group The group name.
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
	 * 
	 * @param string|integer $group Group name
	 * @param string|integer $id Id
	 * @return string
	 */
	public function _($group, $id)
	{
		$groupAdapter = false;
		$adapter = null;
		// Select the adapter.
		if(isset($this->_groupAdapters[$group]))
		{
			$adapter = $this->_groupAdapters[$group];
			$groupAdapter = true;
		}
		else
		{
			$adapter = $this->_defaultAdapter;
		}

		// Select the language
		$languages = array();
		if(isset($this->_groupLanguages[$group]))
		{
			$languages[] = $this->_groupLanguages[$group];
		}
		$languages[] = $this->_currentLanguage;
		$languages[] = $this->_defaultLanguage;

		// Try to get translated message.
		foreach($languages as $lang)
		{
			if(($msg = $adapter->getMessage($lang, $group, $id)) !== null)
			{
				return $msg;
			}
		}
		throw new Opc_TranslateMessageNotFound_Exception($group, $id, $this->_currentLanguage);
	} // end _();

	public function assign($group, $id)
	{
		$data = func_get_args();
		$adapter = null;
		// Filter arrays
		if(is_array($data[2]))
		{
			$data = $data[2];
		}
		else
		{
			unset($data[0], $data[1]);
		}
		// Load the adapter
		if(isset($this->_groupAdapters[$group]))
		{
			$adapter = $this->_groupAdapters[$group];
		}
		else
		{
			$adapter = $this->_defaultAdapter;
		}
		// Select the language
		$languages = array();
		if(isset($this->_groupLanguages[$group]))
		{
			$languages[] = $this->_groupLanguages[$group];
		}
		$languages[] = $this->_currentLanguage;
		$languages[] = $this->_defaultLanguage;
		
		foreach($languages as $lang)
		{
			if($adapter->assign($lang, $group, $id, $data))
			{
				break;
			}
		}
	} // end assign();

	/**
	 * Function chooses new language for messages.
	 *
	 * Sets langauge for translation.
	 * 
	 * @param string $language New language
	 * @return boolean
	 */
	public function setLanguage($language)
	{
		$this->_currentLanguage = $language;
		return true;
	} // end setLanguage();

	/**
	 * Sets the group-specific language for the group. The
	 * null value removes the group language.
	 *
	 * @param string $group Group name
	 * @param string $language The group language
	 */
	public function setGroupLanguage($group, $language)
	{
		if($language === null)
		{
			unset($this->_groupLanguages[$group]);
		}
		else
		{
			$this->_groupLanguages[$group] = (string)$language;
		}
	} // end setGroupLanguage();

	/**
	 * If the group has a custom language defined, returns this
	 * language name. In any other case, it return null.
	 * @param string $group The group name
	 * @return string|null
	 */
	public function getGroupLanguage($group)
	{
		if(isset($this->_groupLanguages[$group]))
		{
			return $this->_groupLanguages[$group];
		}
		return null;
	} // end getGroupLanguage();
	
	/**
	 * Gives access to control default language.
	 * 
	 * @param string $language New default language
	 */
	public function setDefaultLanguage($language)
	{
		$this->_defaultLanguage = $language;
	} // end setDefaultLanguage();
} // end Opc_View_Translation;