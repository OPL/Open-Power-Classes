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
 * The translation adapter representing the message format.
 *
 * @author Tomasz Jędrzejewski <http://www.zyxist.com>
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
abstract class Opc_Translate_Adapter
{
	/**
	 * Returns the message in the specified language.
	 *
	 * @param string $language The language
	 * @param string $group The message group
	 * @param string $msg The message identifier
	 * @return string
	 */
	abstract public function getMessage($language, $group, $msg);

	/**
	 * Assings the data to the specified message.
	 *
	 * @param string $language The language
	 * @param string $group The message group
	 * @param string $msg The message identifier
	 * @param ... The data to assign.
	 */
	abstract public function assign($language, $group, $msg);

	/**
	 * Sets and loads new language files.
	 *
	 * @param string $language New language
	 * @return boolean
	 */
	abstract public function setLanguage($language);

	/**
	 * Returns current language.
	 *
	 * @return String
	 */
	abstract public function getLanguage();

	/**
	 * Sets and loads language for specified group.
	 *
	 * @param string $group Group name
	 * @param string $language New language
	 * @return boolean
	 */
	abstract public function setGroupLanguage($language);

	abstract public function setGroup($group);
} // end Opc_Translate_Adapter;