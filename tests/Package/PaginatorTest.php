<?php
/**
 * The tests for Opc\Paginator.
 *
 * @author Jacek "eXtreme" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */

class Package_PaginatorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the test suite.
	 */
	protected function setUp()
	{
		/*if(!\Opl_Registry::exists('opc'))
		{
			$opc = new \Opc\Core;
		}*/
	} // end setUp();

	/**
	 * Tears down the test suite.
	 */
	protected function tearDown()
	{
		/* null */
	} // end tearDown();

	/**
	 * @covers Opc\Paginator
	 */	 	
	public function testBaseClass()
	{
		if(!\Opl_Registry::exists('opc'))
		{
			$opc = new \Opc\Core;
		}
		$this->assertEquals(false, \Opc\Paginator::getDecoratorClassName('test'));
		\Opc\Paginator::registerDecorator('test', 'TestClass');
		$this->assertEquals('TestClass', \Opc\Paginator::getDecoratorClassName('test'));
		
		$this->assertEquals(0, \Opc\Paginator::countOffset(0, 10));
		$this->assertEquals(0, \Opc\Paginator::countOffset(1, 10));
		$this->assertEquals(10, \Opc\Paginator::countOffset(2, 10));
		
		$this->assertEquals(new \Opc\Paginator\Range(1000, 10), Opc\Paginator::create(1000, 10));
	} // end testBaseClass();
	
	public function testRange1()
	{
		//
	} // end testRange1();
    
    public function testTicket138()
	{
		if(!\Opl_Registry::exists('opc'))
		{
			$opc = new \Opc\Core;
		}
        $paginator = \Opc\Paginator::create(0, 100);
	} // end testRange1();
} // end Package_PaginatorTest;
