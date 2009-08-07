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
 * The error handler extension for OPC.
 * 
 * @author Tomasz "Zyx" Jędrzejewski
 */
class Opc_ErrorHandler extends Opl_ErrorHandler
{
	protected $_library = 'Open Power Classes';
	protected $_context = array(
		'Opc_InvalidArgumentType_Exception' => array(
			'Backtrace' => array(),
			'ErrorInfo' => array(1 => 'The specified argument type is invalid. You have to check your code,
				because OPC cannot continue with this data type as it will cause errors in the later
				execution.'),
		),
		'__UNKNOWN__' => array(
			'BasicConfiguration' => array()
		),
	);

	/**
	 * The informator that prints the basic OPT configuration to the error
	 * output.
	 *
	 * @param Opc_Exception $exception The exception
	 */
	protected function _printBasicConfiguration($exception)
	{
		if(!Opl_Registry::exists('opc'))
		{
			return false;
		}
		$opc = Opl_Registry::get('opc');
		echo '  			<p class="directive">Caching directory: <span>'.htmlspecialchars($opc->cacheDir)."</span></p>\r\n";
		echo '  			<p class="directive">Caching expiry time: <span>'.htmlspecialchars($opc->expiryTime)."</span></p>\r\n";
	} // end _printBasicConfiguration();
} // end Opc_ErrorHandler;
