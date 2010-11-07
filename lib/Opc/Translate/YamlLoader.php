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
use Symfony\Component\Yaml\Parser;

/**
 * A message loader for the translation interface that uses YAML storage.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class YamlLoader extends FileLoader
{
	/**
	 * The YAML parser.
	 * @var \Symfony\Component\Yaml\Parser
	 */
	protected $_parser;

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
		return $this->_getParser()->parse($this->findFile($language.DIRECTORY_SEPARATOR.$group.'.yml'));
	} // end loadLanguageGroup();

	/**
	 * Returns and optionally constructs the parser.
	 * 
	 * @return \Symfony\Component\Yaml\Parser
	 */
	protected function _getParser()
	{
		if($this->_parser == null)
		{
			$this->_parser = new Parser;
		}
		return $this->_parser;
	} // end _getParser();
} // end YamlLoader;