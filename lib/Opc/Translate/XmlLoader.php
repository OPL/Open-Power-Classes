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
 * A message loader for the translation interface that uses XML storage.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class XmlLoader extends FileLoader
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
		$data = simplexml_load_file($this->findFile($language.DIRECTORY_SEPARATOR.$group.'.xml'));

		$messages = array();
		foreach($data->message as $msg)
		{
			if(!isset($msg['name']))
			{
				throw new Exception('Missing \'name\' attribute in the \'message\' tag in \''.$language.DIRECTORY_SEPARATOR.$group.'.xml\' file');
			}
			$messages[(string)$msg['name']] = (string)$msg;
		}
		return $messages;
	} // end loadLanguageGroup();
} // end XmlLoader;