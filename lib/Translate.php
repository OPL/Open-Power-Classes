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
		if(($msg = $adapter->getMessage($this->_defaultLanguage, $group, $id, 'default')) !== null)
		{
			return $msg;
		}
		throw new Opc_TranslateMessageNotFound_Exception($group, $id);
	} // end _();

	public function assign($group, $id)
	{
		if(isset($this->_groupAdapters[$group]))
		{
			$adapter = $this->_groupAdapters[$group];
		}
		else
		{
			$adapter = $this->_defaultAdapter;
		}
		$adapter->assign(func_get_args());
	} // end assign();

	/**
	 * Function chooses new language for messages.
	 *
	 * Returns true if there is language file in translations directory and function
	 * is able to load it, otherwise it returns false and uses default language.
	 * 
	 * @param String $language New language
	 * @return Boolean
	 */
	public function setLanguage($language)
	{
		if($this->_defaultAdapter->setLanguage($language))
		{
			$this->_currentLanguage = $language;
			return true;
		}
		elseif($this->_defaultAdapter->setLanguage($this->_defaultLanguage))
		{
			$this->_currentLanguage = $this->_defaultLanguage;
			return false;
		}
		else
		{
			throw new Opc_TranslateFileNotFound_Exception($language);
		}
	} // end setLanguage();

	/**
	 * Sets language to specified group.
	 *
	 * @param String $group Group name
	 * @param String $language New language
	 * @return Boolean
	 */
	public function setGroupLanguage($group, $language)
	{
		if(isset($this->_groupAdapters[(string)$group]))
		{
			return $this->_groupAdapters[(string)$group]->setGroupLanguage($language);
		}
		else
		{
			return $this->_defaultAdapter->setGroupLanguage($group, $language);
		}
	} // end setGroupLanguage();
	
	/**
	 * Gives access to control default language.
	 * @param String $language New default language
	 */
	public function setDefaultLanguage($language)
	{
		$this->_defaultLanguage = $language;
	} // end setDefaultLanguage();
} // end Opc_View_Translation;