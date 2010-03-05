<?php
/**
 * The tests for Opc_View_Cache.
 *
 * @author Amadeusz 'megawebmaster' Starzykiewicz
 * @copyright Copyright (c) 2010 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */

class Package_ViewCacheTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the test suite.
	 */
	protected function setUp()
	{
		if(!Opl_Registry::exists('opc'))
		{
			$opc = new Opc_Class;
		}
	} // end setUp();

	/**
	 * Tears down the test suite.
	 */
	protected function tearDown()
	{
		/* null */
	} // end tearDown();

	/**
	 * @covers Opc_View_Cache::__construct
	 */
	public function testConstructException()
	{
		Opl_Registry::register('opc', null);
        $this->setExpectedException('Opc_ClassInstanceNotExists_Exception');
		$viewCache = new Opc_View_Cache(array('key' => 'bar', 'cacheDir' => './cache', 'expiryTime' => 100));
	} // end testConstruct();

	/**
	 * @covers Opc_View_Cache::__construct
	 */
	public function testConstruct()
	{
		$viewCache = new Opc_View_Cache(array('key' => 'bar', 'cacheDir' => './cache', 'expiryTime' => 100));
		$this->assertSame('bar', $viewCache->getKey());
		$this->assertSame('./cache/', $viewCache->getCacheDir());
		$this->assertSame(100, $viewCache->getExpiryTime());
	} // end testConstruct();

	/**
	 * @covers Opc_View_Cache::setKey
	 * @covers Opc_View_Cache::getKey
	 */
	public function testKeySetters()
	{
		$viewCache = new Opc_View_Cache;
		$this->assertNull($viewCache->getKey());
		$viewCache->setKey('foo');
		$this->assertSame('foo', $viewCache->getKey());
	} // end testKeySetters();

	/**
	 * @covers Opc_View_Cache::setExpiryTime
	 * @covers Opc_View_Cache::getExpiryTime
	 */
	public function testExpiryTimeSetters()
	{
		$viewCache = new Opc_View_Cache;
		$this->assertSame(3600, $viewCache->getExpiryTime());
		$viewCache->setExpiryTime(200);
		$this->assertSame(200, $viewCache->getExpiryTime());
        $this->setExpectedException('Opc_InvalidArgumentType_Exception');
		$viewCache->setExpiryTime('none');
	} // end testExpiryTimeSetters();

	/**
	 * @covers Opc_View_Cache::setCacheDir
	 * @covers Opc_View_Cache::getCacheDir
	 */
	public function testCacheDirSetters()
	{
		$viewCache = new Opc_View_Cache;
		$this->assertSame('', $viewCache->getCacheDir());
		$viewCache->setCacheDir('./bar/');
		$this->assertSame('./bar/', $viewCache->getCacheDir());
		$viewCache->setCacheDir('./foo');
		$this->assertSame('./foo/', $viewCache->getCacheDir());
	} // end testCacheDirSetters();
} // end Package_ViewCacheTest;