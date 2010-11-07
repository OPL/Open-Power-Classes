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
 * The interface for message loaders.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
interface LoaderInterface
{
	/**
	 * Loads the messages for the given group.
	 *
	 * @param string $language The language locale
	 * @param string|integer $group The group name
	 * @return array
	 */
	public function loadLanguageGroup($language, $group);
} // end LoaderInterface;