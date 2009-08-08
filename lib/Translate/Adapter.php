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
 * The translation adapter representing the message format.
 *
 * @author Tomasz Jędrzejewski <http://www.zyxist.com>
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 */
abstract class Opc_Translate_Adapter
{
	/**
	 * Returns the message in the specified language.
	 *
	 * @param String $language The language
	 * @param String $group The message group
	 * @param String $msg The message identifier
	 * @return String
	 */
	abstract public function getMessage($language, $group, $msg);

	/**
	 * Assings the data to the specified message.
	 *
	 * @param String $language The language
	 * @param String $group The message group
	 * @param String $msg The message identifier
	 * @param ... The data to assign.
	 */
	abstract public function assign($language, $group, $msg);

	/**
	 * Sets and loads new language files.
	 *
	 * @param String $language New language
	 * @return Boolean
	 */
	abstract public function setLanguage($language);

	/**
	 * Sets and loads language for specified group.
	 *
	 * @param String $group Group name
	 * @param String $language New language
	 * @return Boolean
	 */
	abstract public function setGroupLanguage($group, $language);
} // end Opc_Translate_Adapter;