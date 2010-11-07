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
 * An abstract class for loading the data from the files.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
abstract class FileLoader implements LoaderInterface
{
	/**
	 * The list of scanned paths.
	 * @var array
	 */
	protected $_paths;

	/**
	 * Creates the file loader.
	 *
	 * @param array|string $paths The list of paths, where to look for the files.
	 */
	public function __construct($paths = array())
	{
		if(!is_array($paths))
		{
			$paths = array($paths);
		}
		foreach($paths as &$path)
		{
			if($path[strlen($path) - 1] != DIRECTORY_SEPARATOR)
			{
				$path .= DIRECTORY_SEPARATOR;
			}
		}
		$this->_paths = $paths;
	} // end __construct();

	/**
	 * Returns a validated path to the specified file. If the file name is
	 * invalid or it does not exist in any of defined paths, an exception
	 * is thrown.
	 *
	 * @throws InvalidArgumentException
	 * @param string $filename The file name
	 * @return string
	 */
	public function findFile($filename)
	{
		foreach($this->_paths as $path)
		{
			if(file_exists($path.$filename))
			{
				return $path.$filename;
			}
		}
		throw new \InvalidArgumentException('The file \''.$filename.'\' does not exist in any of the specified paths.');
	} // end findFile();
} // end FileLoader;