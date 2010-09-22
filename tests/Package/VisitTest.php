<?php
/**
 * The tests for Opc\Visit.
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */

/**
 * @covers Opc\Visit
 */
class Package_VisitTest extends PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		$visit = new \Opc\Visit();
		$visit->reset();
	} // end setUp();

	protected function tearDown()
	{
	} // end tearDown();

	/**
	 * @covers Opc\Visit::__get
	 * @covers Opc\Visit::get
	 */
	public function testIP()
	{
		$visit = new \Opc\Visit();
		$_SERVER['REMOTE_ADDR'] = '12.34.56.78';
		$this->assertEquals('12.34.56.78', $visit->ip);
	} // end testIP();

	/**
	 * @covers Opc\Visit::__get
	 * @covers Opc\Visit::get
	 */
	public function testNumericIP()
	{
		$visit = new \Opc\Visit();
		$_SERVER['REMOTE_ADDR'] = '12.34.56.78';
		$this->assertEquals(ip2long('12.34.56.78'), $visit->numericIp);
	} // end testNumericIP();

	/**
	 * @covers Opc\Visit::__get
	 * @covers Opc\Visit::get
	 */
	public function testHost()
	{
		$visit = new \Opc\Visit();
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		$this->assertTrue(strpos($visit->host, 'localhost') !== false);
	} // end testHost();

	/**
	 * @covers Opc\Visit::__get
	 * @covers Opc\Visit::get
	 */
	public function testProtocolHttp()
	{
		$visit = new \Opc\Visit();
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP';
		$this->assertEquals('http', $visit->protocol);
	} // end testProtocolHttp();

	/**
	 * @covers Opc\Visit::__get
	 * @covers Opc\Visit::get
	 */
	public function testProtocolHttps()
	{
		$visit = new \Opc\Visit();
		$_SERVER['SERVER_PROTOCOL'] = 'HTTPS';
		$this->assertEquals('https', $visit->protocol);
	} // end testProtocolHttps();

	/**
	 * @covers Opc\Visit::__get
	 * @covers Opc\Visit::get
	 */
	public function testProtocolWap()
	{
		$visit = new \Opc\Visit();
		$_SERVER['SERVER_PROTOCOL'] = 'WAP';
		$this->assertEquals('wap', $visit->protocol);
	} // end testProtocolWap();

	/**
	 * @covers Opc\Visit::__get
	 * @covers Opc\Visit::get
	 */
	public function testProtocolOther()
	{
		$visit = new \Opc\Visit();
		$_SERVER['SERVER_PROTOCOL'] = 'IMGW';
		$this->assertEquals('unknown', $visit->protocol);
	} // end testProtocolOther();
} // end Package_VisitTest;