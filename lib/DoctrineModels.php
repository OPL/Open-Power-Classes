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
 * An utility class which helps autoload models in Doctrine ORM.
 * 
 * @author Amadeusz "megawebmaster" Starzykiewicz
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Opc_DoctrineModels
{
	
	/**
	 * Models path
	 * @static
	 * @access private
	 * @var string
	 */
	static protected $_modelsPath;
	/**
	 * Base model prefix
	 * @static
	 * @access private
	 * @var string
	 */
	static protected $_baseModelPrefix = 'Base';
	/**
	 * Generated models directory name
	 * @static
	 * @access private
	 * @var string
	 */
	static protected $_generatedModelsDirectoryName = 'generated';
	/**
	 * Class file suffix
	 * @static
	 * @access private
	 * @var string
	 */
	static protected $_classFileSuffix = '.php';

	/**
	 * An autoloader method.
	 *
	 * @static
	 * @param string $className
	 * @return boolean
	 */
	static public function autoload($className)
	{
		// Check if class is properly initialized (models dir is set)
		if(!empty(self::$_modelsPath))
		{
			$base = self::$_modelsPath;

			// Check if this is a base model
			if(substr($className, 0, strlen(self::$_baseModelPrefix)) == self::$_baseModelPrefix)
			{
				$base .= self::$_generatedModelsDirectoryName.DIRECTORY_SEPARATOR;
			}

			// We need to check if file exists.
			if(file_exists($base.$className.self::$_classFileSuffix))
			{
				require($base.$className.self::$_classFileSuffix);
				return true;
			}
		}
		
		return false;
	} // end autoload();

	/**
	 * Allows to set generated base models prefix.
	 *
	 * @static
	 * @param string $name Base model prefix
	 */
	static public function setBaseModelPrefix($name)
	{
		self::$_baseModelPrefix = $name;
	} // end setBaseModelPrefix();
	
	/**
	 * Allows to set different suffix for class files.
	 *
	 * @static
	 * @param string $suffix File suffix
	 */
	static public function setClassFileSuffix($suffix)
	{
		if($suffix[0] != '.')
		{
			$suffix = '.'.$suffix;
		}
		self::$_classFileSuffix = $suffix;
	} // end setClassFileSuffix();

	/**
	 * Allows to set generated base models directory name.
	 *
	 * @static
	 * @param string $dir Directory name
	 */
	static public function setGeneratedModelsDirectoryName($dir)
	{
		if(!empty($dir))
		{
			$dir = trim($dir, '/\\');
			self::$_generatedModelsDirectoryName = $dir;
		}
		else
		{
			throw new Opc_DoctrineModels_InvalidDirectoryName_Exception();
		}

	} // end setGeneratedModelsDirectoryName();

	/**
	 * Specifies directory path to Doctrine models.
	 *
	 * @static
	 * @param string $path Full path to models directory
	 */
	static public function setPath($path)
	{
		if(!empty($path))
		{
			$path = rtrim($path, '/\\');
			$path .= DIRECTORY_SEPARATOR;
		}
		self::$_modelsPath = $path;
	} // end setPath();

	/**
	 * Registers an autoloader.
	 * 
	 * @static
	 */
	static public function register()
	{
		spl_autoload_register(array('Opc_DoctrineModels', 'autoload'));
	} // end register();
} // end Opc_DoctrineModels;
