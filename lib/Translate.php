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
		$_groupsLanguage = array(),
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

		// Check if there is set language.
		if($this->_currentLanguage === null)
		{
			if($groupAdapter)
			{
				$this->setGroupLanguage($group, $this->_defaultLanguage);
			}
			else
			{
				$this->setLanguage($this->_defaultLanguage);
			}
		}
		// Try to get translated message.
		if(($msg = $adapter->getMessage($this->_currentLanguage, $group, $id)) !== null)
		{
			return $msg;
		}
		// Try to load current language
		if($adapter->loadGroupLanguage($group, $this->_currentLanguage))
		{
			// Try to get translated message if language is loaded now
			if(($msg = $adapter->getMessage($this->_currentLanguage, $group, $id)) !== null)
			{
				return $msg;
			}
		}
		// Try to get default message.
		if(($msg = $adapter->getMessage($this->_defaultLanguage, $group, $id, 'default')) !== null)
		{
			return $msg;
		}
		// Try to load default language
		if($adapter->loadGroupLanguage($group, $this->_defaultLanguage))
		{
			// Try to get default message if language is loaded now
			if(($msg = $adapter->getMessage($this->_defaultLanguage, $group, $id)) !== null)
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
		unset($data[0],$data[1]);
		if(isset($this->_groupAdapters[$group]))
		{
			$adapter = $this->_groupAdapters[$group];
		}
		else
		{
			$adapter = $this->_defaultAdapter;
		}
		$adapter->assign($group, $id, $data);
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
	 * Sets language to specified group.
	 *
	 * @param string $group Group name
	 * @param string $language New language
	 * @return boolean
	 */
	public function setGroupLanguage($group, $language)
	{
		if(isset($this->_groupAdapters[$group]))
		{
			if($this->_groupAdapters[$group]->loadGroupLanguage($group, $language))
			{
				$this->_groupsLanguage[$group] = $language;
				return true;
			}
			elseif($this->_groupAdapters[$group]->loadGroupLanguage($group, $this->_defaultLanguage, 'default'))
			{
				$this->_groupsLanguage[$group] = $this->_defaultLanguage;
				return false;
			}
			else
			{
				throw new Opc_TranslateFileNotFound_Exception($language, 'translation');
			}
		}
		else
		{
			if($this->_defaultAdapter->loadGroupLanguage($group, $language))
			{
				$this->_groupsLanguage[$group] = $language;
				return true;
			}
			elseif($this->_defaultAdapter->loadGroupLanguage($group, $this->_defaultLanguage, 'default'))
			{
				$this->_groupsLanguage[$group] = $this->_defaultLanguage;
			}
			return false;
		}
	} // end setGroupLanguage();
	
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