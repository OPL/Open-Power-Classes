<?php
/**
 * The tests for Opc_Paginator.
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
		
	} // end setUp();

	/**
	 * Tears down the test suite.
	 */
	protected function tearDown()
	{
		/* null */
	} // end tearDown();

	/**
	 * @covers Opc_Paginator
	 */	 	
	public function testBaseClass()
	{
		$opc = new Opc_Class;
		$this->assertEquals(false, Opc_Paginator::getDecoratorClassName('test'));
		Opc_Paginator::registerDecorator('test', 'TestClass');
		$this->assertEquals('TestClass', Opc_Paginator::getDecoratorClassName('test'));
		
		$this->assertEquals(0, Opc_Paginator::countOffset(0, 10));	
		$this->assertEquals(0, Opc_Paginator::countOffset(1, 10));
		$this->assertEquals(10, Opc_Paginator::countOffset(2, 10));
		
		$this->assertEquals(new Opc_Paginator_Range(1000, 10), Opc_Paginator::create(1000, 10));
	} // end testBaseClass();
	
	public function testRange1()
	{
		$opc = new Opc_Class;
	} // end testRange1();
    
    public function testTicket138()
	{
		$opc = new Opc_Class;
        
        $paginator = Opc_Paginator::create(0, 100);
	} // end testRange1();
} // end Package_TranslateTest;
