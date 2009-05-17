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
