<?php
/**
 * The tests for Opc_Translate.
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
	 * @covers Opc_Translate::setAdapter
	 * @covers Opc_Translate::getAdapter
	 */
	public function testAdapterSetters()
	{
		$translate = new Opc_Translate($this->getMock('Opc_Translate_Adapter'));

		$translate->setAdapter($obj = $this->getMock('Opc_Translate_Adapter'));
		$this->assertSame($obj, $translate->getAdapter());
	} // end testAdapterSetters();

	/**
	 * @covers Opc_Translate::__construct
	 */
	public function testConstructor()
	{
		$translate = new Opc_Translate($obj = $this->getMock('Opc_Translate_Adapter'));
		$this->assertSame($obj, $translate->getAdapter());
	} // end testConstructor();

	/**
	 * @covers Opc_Translate::setGroupAdapter
	 * @covers Opc_Translate::getGroupAdapter
	 */
	public function testGroupAdapterSetter()
	{
		$translate = new Opc_Translate($obj1 = $this->getMock('Opc_Translate_Adapter'));
		$translate->setGroupAdapter('foo', $obj2 = $this->getMock('Opc_Translate_Adapter'));
		$this->assertSame($obj2, $translate->getGroupAdapter('foo'));
	} // end testGroupAdapterSetter();

	/**
	 * getGroupAdapter should return the default adapter for the undefined group.
	 *
	 * @covers Opc_Translate::getGroupAdapter
	 */
	public function testGroupAdapterSetterDefault()
	{
		$translate = new Opc_Translate($obj1 = $this->getMock('Opc_Translate_Adapter'));
		$translate->setGroupAdapter('foo', $obj2 = $this->getMock('Opc_Translate_Adapter'));
		$this->assertSame($obj1, $translate->getGroupAdapter('bar'));
	} // end testGroupAdapterSetterDefault();
} // end Package_TranslateTest;