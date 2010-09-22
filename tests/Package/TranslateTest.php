<?php
/**
 * The tests for Opc\Translate.
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */

class Package_TranslateTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the test suite.
	 */
	protected function setUp()
	{
		/* null */
	} // end setUp();

	/**
	 * Tears down the test suite.
	 */
	protected function tearDown()
	{
		/* null */
	} // end tearDown();

	/**
	 * @covers Opc\Translate::setAdapter
	 * @covers Opc\Translate::getAdapter
	 */
	public function testAdapterSetters()
	{
		$translate = new \Opc\Translate($this->getMock('\Opc\Translate\Adapter'));

		$translate->setAdapter($obj = $this->getMock('\Opc\Translate\Adapter'));
		$this->assertSame($obj, $translate->getAdapter());
	} // end testAdapterSetters();

	/**
	 * @covers Opc\Translate::__construct
	 */
	public function testConstructor()
	{
		$translate = new \Opc\Translate($obj = $this->getMock('\Opc\Translate\Adapter'));
		$this->assertSame($obj, $translate->getAdapter());
	} // end testConstructor();

	/**
	 * @covers Opc\Translate::setGroupAdapter
	 * @covers Opc\Translate::getGroupAdapter
	 */
	public function testGroupAdapterSetter()
	{
		$translate = new \Opc\Translate($obj1 = $this->getMock('\Opc\Translate\Adapter'));
		$translate->setGroupAdapter('foo', $obj2 = $this->getMock('\Opc\Translate\Adapter'));
		$this->assertSame($obj2, $translate->getGroupAdapter('foo'));
	} // end testGroupAdapterSetter();

	/**
	 * getGroupAdapter should return the default adapter for the undefined group.
	 *
	 * @covers Opc\Translate::getGroupAdapter
	 */
	public function testGroupAdapterSetterDefault()
	{
		$translate = new \Opc\Translate($obj1 = $this->getMock('\Opc\Translate\Adapter'));
		$translate->setGroupAdapter('foo', $obj2 = $this->getMock('\Opc\Translate\Adapter'));
		$this->assertSame($obj1, $translate->getGroupAdapter('bar'));
	} // end testGroupAdapterSetterDefault();
} // end Package_TranslateTest;