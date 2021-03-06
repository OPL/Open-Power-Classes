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
		$_defaultLanguage = 'en',
		/**
		 * Opc_Class instance.
		 * @var Opc_Class
		 */
		$_opc = null;

	/**
	 * Creates the new translation object.
	 * 
	 * @param Opc_Translate_Adapter $adapter The default translation adapter.
	 */
	public function __construct(Opc_Translate_Adapter $adapter)
	{
		if(!Opl_Registry::exists('opc'))
		{
			throw new Opc_ClassInstanceNotExists_Exception;
		}
		$this->_opc = Opl_Registry::get('opc');
		$this->_defaultLanguage = $this->_opc->defaultLanugage;
		$this->_defaultAdapter = $adapter;
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
		$this->_groupAdapters[$group] = $adapter;
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
		if($groupAdapter)
		{
			if(!isset($this->_groupsLanguage[$group]))
			{
				$this->setGroupLanguage($this->_defaultLanguage, $group);
			}
		}
		elseif($this->_currentLanguage === null)
		{
			$this->setLanguage($this->_defaultLanguage);
		}
		// Try to get translated message.
		if(($msg = $adapter->getMessage($this->_currentLanguage, $group, $id)) !== null)
		{
			return $msg;
		}
		// Try to load group for language
		if($this->setGroupLanguage($this->_currentLanguage, $group))
		{
			// Try to get that loaded message
			if(($msg = $adapter->getMessage($this->_currentLanguage, $group, $id)) !== null)
			{
				return $msg;
			}
		}
		// Try to get default message.
		if(($msg = $adapter->getMessage($this->_defaultLanguage, $group, $id)) !== null)
		{
			return $msg;
		}
		// Try to load group for default language
		if($this->setGroupLanguage($this->_defaultLanguage, $group))
		{
			// Try to get that loaded message
			if(($msg = $adapter->getMessage($this->_defaultLanguage, $group, $id)) !== null)
			{
				return $msg;
			}
		}
		throw new Opc_Translate_MessageNotFound_Exception($group, $id, $this->_currentLanguage);
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
		$language = null;
		$adapter->assign($language, $group, $id, $data);
	} // end assign();

	/**
	 * Function chooses new language for messages.
	 *
	 * Returns true if there is language file in translations directory and function
	 * is able to load it, otherwise it returns false and uses default language.
	 * 
	 * @param string $language New language
	 * @return boolean
	 */
	public function setLanguage($language)
	{
		if($this->_currentLanguage != $language)
		{
			if($this->_defaultAdapter->loadLanguage($language))
			{
				$this->_currentLanguage = $language;
				return true;
			}
			elseif($this->_defaultAdapter->loadLanguage($this->_defaultLanguage))
			{
				$this->_currentLanguage = $this->_defaultLanguage;
				return false;
			}
			else
			{
				throw new Opc_Translate_NoTranslationLoaded_Exception();
			}
		}
	} // end setLanguage();

	public function getLanguage()
	{
		return $this->_currentLanguage;
	} // end getLanguage();

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
			if($this->_groupAdapters[$group]->loadGroupLanguage($language, $group))
			{
				$this->_groupsLanguage[$group] = $language;
				return true;
			}
			elseif($this->_groupAdapters[$group]->loadGroupLanguage($this->_defaultLanguage, $group))
			{
				$this->_groupsLanguage[$group] = $this->_defaultLanguage;
				return false;
			}
			else
			{
				throw new Opc_Translate_NoTranslationLoaded_Exception();
			}
		}
		elseif($this->_currentLanguage != $language)
		{
			if($this->_defaultAdapter->loadGroupLanguage($group, $language))
			{
				$this->_groupsLanguage[$group] = $language;
				return true;
			}
			elseif($this->_defaultAdapter->loadGroupLanguage($group, $this->_defaultLanguage))
			{
				$this->_groupsLanguage[$group] = $this->_defaultLanguage;
				return false;
			}
			else
			{
				throw new Opc_Translate_NoTranslationLoaded_Exception();
			}
		}
	} // end setGroupLanguage();

	public function getGroupLanguage($group)
	{
		return isset($this->_groupsLanguage[$group])?$this->_groupsLanguage[$group]:null;
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

	public function getDefaultLanguage()
	{
		return $this->_defaultLanguage;
	} // end getDefaultLanguage();
} // end Opc_View_Translation;