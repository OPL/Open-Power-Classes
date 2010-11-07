<?php
/*
 *  OPEN POWER LIBS <http://www.invenzzia.org>
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 */
namespace Opc\Translate;

/**
 * A message loader for the translation interface that uses INI storage.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class IniLoader extends FileLoader
{
	/**
	 * Loads the message group for the given language.
	 *
	 * @throws \Opc\Translate\Exception
	 * @param string $language
	 * @param string|integer $group
	 * @return array
	 */
	public function loadLanguageGroup($language, $group)
	{
		return parse_ini_file($this->findFile($language.DIRECTORY_SEPARATOR.$group.'.ini'));
	} // end loadLanguageGroup();
} // end IniLoader;