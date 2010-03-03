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
	 * Assings the data to the specified message. The method must return
	 * true, if the assignment was successful and false otherwise.
	 *
	 * @param string $language The language
	 * @param string $group The message group
	 * @param string $id The message identifier
	 * @param array $data The data to assign
	 * @return boolean
	 */
	abstract public function assign($language, $group, $id, array $data);

	/**
	 * Loads new language.
	 *
	 * @param string $language New language
	 */
	abstract public function loadLanguage($language);

	/**
	 * Loads new language for specified group.
	 *
	 * @param string $language New language
	 * @param string $group Group name
	 */
	abstract public function loadGroupLanguage($language, $group);
} // end Opc_Translate_Adapter;