<?php
/**
 * The tests for Opc\View\Cache.
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
		if(!\Opl_Registry::exists('opc'))
		{
			$opc = new \Opc\Core;
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
	 * @covers Opc\View\Cache::__construct
	 */
	public function testConstructException()
	{
		\Opl_Registry::set('opc', null);
        $this->setExpectedException('\Opc\View\Exception');
		$viewCache = new \Opc\View\Cache(array('key' => 'bar', 'cacheDir' => './cache', 'expiryTime' => 100));
	} // end testConstruct();

	/**
	 * @covers Opc\View\Cache::__construct
	 */
	public function testConstruct()
	{
		$viewCache = new \Opc\View\Cache(array('key' => 'bar', 'directory' => './cache', 'expiryTime' => 100));
		$this->assertSame('bar', $viewCache->getKey());
		$this->assertSame('./cache/', $viewCache->getDirectory());
		$this->assertSame(100, $viewCache->getExpiryTime());
	} // end testConstruct();

	/**
	 * @covers Opc\View\Cache::setKey
	 * @covers Opc\View\Cache::getKey
	 */
	public function testKeySetters()
	{
		$viewCache = new \Opc\View\Cache;
		$this->assertNull($viewCache->getKey());
		$viewCache->setKey('foo');
		$this->assertSame('foo', $viewCache->getKey());
	} // end testKeySetters();

	/**
	 * @covers Opc\View\Cache::setExpiryTime
	 * @covers Opc\View\Cache::getExpiryTime
	 */
	public function testExpiryTimeSetters()
	{
		$viewCache = new \Opc\View\Cache();
		$this->assertSame(3600, $viewCache->getExpiryTime());
		$viewCache->setExpiryTime(200);
		$this->assertSame(200, $viewCache->getExpiryTime());
        $this->setExpectedException('\Opc\View\Exception');
		$viewCache->setExpiryTime('none');
	} // end testExpiryTimeSetters();

	/**
	 * @covers Opc\View\Cache::setDirectory
	 * @covers Opc\View\Cache::getDirectory
	 */
	public function testCacheDirSetters()
	{
		$viewCache = new \Opc\View\Cache;
		$this->assertSame('', $viewCache->getDirectory());
		$viewCache->setDirectory('./bar/');
		$this->assertSame('./bar/', $viewCache->getDirectory());
		$viewCache->setDirectory('./foo');
		$this->assertSame('./foo/', $viewCache->getDirectory());
	} // end testCacheDirSetters();
} // end Package_ViewCacheTest;