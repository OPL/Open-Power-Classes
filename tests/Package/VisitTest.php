<?php
/**
 * The tests for Opc_Visit.
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */

/**
 * @covers Opc_Visit
 */
class Package_VisitTest extends PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		$visit = Opc_Visit::getInstance();
		$visit->reset();
	} // end setUp();

	protected function tearDown()
	{
	} // end tearDown();

	/**
	 * @covers Opc_Visit::__get
	 * @covers Opc_Visit::get
	 */
	public function testIP()
	{
		$visit = Opc_Visit::getInstance();
		$_SERVER['REMOTE_ADDR'] = '12.34.56.78';
		$this->assertEquals('12.34.56.78', $visit->ip);
	} // end testIP();

	/**
	 * @covers Opc_Visit::__get
	 * @covers Opc_Visit::get
	 */
	public function testNumericIP()
	{
		$visit = Opc_Visit::getInstance();
		$_SERVER['REMOTE_ADDR'] = '12.34.56.78';
		$this->assertEquals(ip2long('12.34.56.78'), $visit->numericIp);
	} // end testNumericIP();

	/**
	 * @covers Opc_Visit::__get
	 * @covers Opc_Visit::get
	 */
	public function testHost()
	{
		$visit = Opc_Visit::getInstance();
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		$this->assertTrue(strpos($visit->host, 'localhost') !== false);
	} // end testHost();

	/**
	 * @covers Opc_Visit::__get
	 * @covers Opc_Visit::get
	 */
	public function testProtocolHttp()
	{
		$visit = Opc_Visit::getInstance();
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP';
		$this->assertEquals('http', $visit->protocol);
	} // end testProtocolHttp();

	/**
	 * @covers Opc_Visit::__get
	 * @covers Opc_Visit::get
	 */
	public function testProtocolHttps()
	{
		$visit = Opc_Visit::getInstance();
		$_SERVER['SERVER_PROTOCOL'] = 'HTTPS';
		$this->assertEquals('https', $visit->protocol);
	} // end testProtocolHttps();

	/**
	 * @covers Opc_Visit::__get
	 * @covers Opc_Visit::get
	 */
	public function testProtocolWap()
	{
		$visit = Opc_Visit::getInstance();
		$_SERVER['SERVER_PROTOCOL'] = 'WAP';
		$this->assertEquals('wap', $visit->protocol);
	} // end testProtocolWap();

	/**
	 * @covers Opc_Visit::__get
	 * @covers Opc_Visit::get
	 */
	public function testProtocolOther()
	{
		$visit = Opc_Visit::getInstance();
		$_SERVER['SERVER_PROTOCOL'] = 'IMGW';
		$this->assertEquals('unknown', $visit->protocol);
	} // end testProtocolOther();
} // end Package_VisitTest;