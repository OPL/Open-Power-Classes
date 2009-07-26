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

class Opc_Exception extends Opl_Exception{}

class Opc_InvalidArgumentType_Exception extends Opc_Exception
{
	protected $_message = 'The method got %s data type, %s expected.';
} // end Opc_InvalidArgumentType_Exception;

class Opc_OptionNotExists_Exception extends Opc_Exception
{
	protected $_message = 'The option "%s" does not exist in %s.';
} // end Opc_OptionNotExists_Exception;

class Opc_OptionReadOnly_Exception extends Opc_Exception
{
	protected $_message = 'The option "%s" is read-only in %s and cannot be set.';
} // end Opc_OptionNotExists_Exception;

class Opc_OptionInvalid_Exception extends Opc_Exception
{
	protected $_message = 'The option "%s" in %s is invalid. Expected: %s.';
} // end Opc_OptionNotExists_Exception;

class Opc_PaginatorNoData_Exception extends Opc_Exception
{

} // end Opc_PaginatorNoData_Exception;

class Opc_PaginatorUndefinedDecorator_Exception extends Opc_Exception
{
	protected $_message = 'Undefined decorator "%s".';
} // end Opc_PaginatorUndefinedDecorator_Exception;

class Opc_PaginatorWrongSubclassDecorator_Exception extends Opc_Exception
{
	protected $_message = 'Given decorator "%s" is not a subclass of "Opc_Paginator_Decorator".';
} // end Opc_PaginatorWrongSubclassDecorator_Exception;

class Opc_DoctrineModelsInvalidDirectoryName_Exception extends Opc_Exception
{
	protected $_message = 'Given Doctrine generated models folder name is invalid.';
} // end Opc_DoctrineModelsGeneratedNameInvalid_Exception;

class Opc_ViewHasInvalidDynamicContent_Exception extends Opc_Exception
{
	protected $_message = 'View %s has dynamic content, but file with it is broken. Cache cannot be generated';
} // end Opc_ViewHasInvalidDynamicContent_Exception;

class Opc_CannotSaveCacheFile_Exception extends Opc_Exception
{
	protected $_message = 'Cache file could not be saved. Has PHP permission to write in cache directory "%s"?';
} // end Opc_CannotSaveCacheFile_Exception;
