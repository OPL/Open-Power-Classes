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
 *
 */
namespace Opc;
use MessageFormatter;
use SplPriorityQueue;
use Opl_Translation_Interface;
use Opc\Translate\LoaderInterface;
use Opc\Translate\CachingInterface;

/**
 * The class represents a translation interface for OPL.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Translate implements Opl_Translation_Interface
{
	/**
	 * The identifier of the primary language.
	 * @var SplPriorityQueue
	 */
	protected $_languages;

	/**
	 * The stored messages.
	 * @var array
	 */
	protected $_messages = array();

	/**
	 * Data-assigned messages.
	 * @var array
	 */
	protected $_dataMessages = array();

	/**
	 * The list of groups, where the base language has been applied.
	 * @var array
	 */
	protected $_baseLoads = array();

	/**
	 * The translation loader
	 * @var \Opc\Translate\LoaderInterface
	 */
	protected $_loader;

	/**
	 * The caching interface
	 * @var \Opc\Translate\CachingInterface
	 */
	protected $_cache;

	/**
	 * Initializes the translation object.
	 *
	 * @param \Opc\Translate\CachingInterface $cache Caching system
	 * @param \Opc\Translate\LoaderInterface $loader Translation message loader
	 */
	public function __construct(CachingInterface $cache, LoaderInterface $loader)
	{
		$this->_cache = $cache;
		$this->_loader = $loader;
		$this->_languages = new SplPriorityQueue;
	} // end __construct();

	/**
	 * Adds the language used by the translator. The priority determines the
	 * order which the languages will be verified in. Implements fluent
	 * interface. The method implements fluent interface.
	 *
	 * @param string $language The language locale
	 * @param int $priority The language priority
	 * @return \Opc\Translate
	 */
	public function addLanguage($language, $priority)
	{
		$this->_languages->insert((string)$language, (int)$priority);
	} // end addLanguage();

	/**
	 * Checks if the given language is already registered. Note that
	 * this implementation has complexity of O(n), so try not to use
	 * it too often.
	 *
	 * @param string $language The searched language.
	 * @return boolean
	 */
	public function hasLanguage($language)
	{
		foreach($this->_languages as $checkedLanguage)
		{
			if($checkedLanguage == $language)
			{
				return true;
			}
		}
		return false;
	} // end hasLanguage();

	/**
	 * Returns the list of registered languages in the order of their
	 * priorities.
	 *
	 * @return array
	 */
	public function getLanguages()
	{
		$list = array();
		foreach($this->_languages as $language)
		{
			$list[] = $language;
		}
		return $list;
	} // end getLanguages();

	/**
	 * Returns a translation for the specified message identifier stored
	 * in a given group. If the primary language is missing the translation,
	 * it attempts to load the base language group.
	 *
	 * @param string|integer $group Translation group
	 * @param string|integer $id Message identifier within the group
	 * @return string
	 */
	public function _($group, $id)
	{
		// Messages with data assigned
		if(isset($this->_dataMessages[$group.'@'.$id]))
		{
			return $this->_dataMessages[$group.'@'.$id];
		}
		$void = null;
		return $this->_getRawMessage($group, $id, $void);
	} // end _();

	/**
	 * Assigns the arguments to the given message. The message should be
	 * formatted using the ICU message formatting rules.
	 *
	 * @param string|integer $group Translation group
	 * @param string|integer $id Message identifier within the group
	 * @param array $args
	 */
	public function assign($group, $id, array $args)
	{
		$locale = null;
		$message = $this->_getRawMessage($group, $id, $locale);
		if(!($message instanceof MessageFormatter))
		{
			$messageObj = new MessageFormatter($locale, $message);
		
			if($messageObj === null)
			{
				throw new Exception('Invalid message format: \''.$message.'\' (\''.$group.'@'.$id.'\')');
			}

			$this->_messages[$locale][$group][$id] = $message = $messageObj;
		}
		$this->_dataMessages[$group.'@'.$id] = $message->format($args);
	} // end assign();

	/**
	 * Returns the message for the given id, probing the languages according
	 * to their priority. In the last argument, the matching language locale
	 * is passed.
	 *
	 * If the message is missing in all the languages, an exception is thrown.
	 *
	 * @throws Opc\Exception
	 * @param string|integer $group Translation group
	 * @param string|integer $id Message identifier within the group
	 * @param string $locale A reference to a variable, where the matching locale should be stored.
	 * @return <type>
	 */
	protected function _getRawMessage($group, $id, &$locale)
	{
		foreach($this->_languages as $language)
		{
			if(!isset($this->_messages[$language]))
			{
				$this->_messages[$language] = array();
			}
			if(!isset($this->_messages[$language][$group]))
			{
				if($this->_cache->hasGroup($language, $group))
				{
					$this->_messages[$language][$group] = $this->_cache->getGroup($language, $group);
				}
				else
				{
					$this->_cache->setGroup($language, $group,
						$this->_messages[$language][$group] = $this->_loader->loadLanguageGroup($language, $group)
					);
				}
			}
			if(isset($this->_messages[$language][$group][$id]))
			{
				$locale = $language;
				return $this->_messages[$language][$group][$id];
			}
		}
		throw new Exception('Missing translation for \''.$group.'@'.$id.'\'');
	} // end _getRawMessage();
} // end Translate;