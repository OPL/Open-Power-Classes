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
	 * @cover \Opc\Translate::addLanguage
	 * @cover \Opc\Translate::getLanguages
	 */
	public function testRegisteringLanguages()
	{
		$translator = new \Opc\Translate($this->getMock('\Opc\Translate\CachingInterface'), $this->getMock('\Opc\Translate\LoaderInterface'));
		$translator->addLanguage('pl_PL', 10);
		$translator->addLanguage('en_EN', 0);
		$translator->addLanguage('de_DE', 20);

		$this->assertEquals(array('de_DE', 'pl_PL', 'en_EN'), $translator->getLanguages());
	} // end testRegisteringLanguages();

	/**
	 * @cover \Opc\Translate::hasLanguage
	 */
	public function testHasLanguage()
	{
		$translator = new \Opc\Translate($this->getMock('\Opc\Translate\CachingInterface'), $this->getMock('\Opc\Translate\LoaderInterface'));
		$translator->addLanguage('pl_PL', 10);
		$this->assertTrue($translator->hasLanguage('pl_PL'));
		$this->assertFalse($translator->hasLanguage('en_EN'));
	} // end testHasLanguage();

	/**
	 * @cover \Opc\Translate::_
	 */
	public function testMessageLoadingFromLoader()
	{
		$cacheMock = $this->getMock('\Opc\Translate\CachingInterface');
		$cacheMock->expects($this->once())
			->method('hasGroup')
			->with($this->stringContains('pl_PL'), $this->stringContains('foo'))
			->will($this->returnValue(false));

		$loaderMock = $this->getMock('\Opc\Translate\LoaderInterface');
		$loaderMock->expects($this->once())
			->method('loadLanguageGroup')
			->with($this->stringContains('pl_PL'), $this->stringContains('foo'))
			->will($this->returnValue(array('teapot' => 'Jestem czajnikiem.')));

		$translator = new \Opc\Translate($cacheMock, $loaderMock);
		$translator->addLanguage('pl_PL', 10);
		$this->assertEquals('Jestem czajnikiem.', $translator->_('foo', 'teapot'));
	} // end testMessageLoadingFromLoader();

	/**
	 * @cover \Opc\Translate::_
	 */
	public function testMessageLoadingMoreLanguages()
	{
		$cacheMock = $this->getMock('\Opc\Translate\CachingInterface');
		$cacheMock->expects($this->exactly(2))
			->method('hasGroup')
			->will($this->returnValue(false));

		$loaderMock = $this->getMock('\Opc\Translate\LoaderInterface');
		$loaderMock->expects($this->exactly(2))
			->method('loadLanguageGroup')
			->will($this->returnCallback(function($language, $group){
				if($language == 'pl_PL' && $group == 'foo')
				{
					return array('teapot' => 'Jestem czajnikiem.');
				}
				elseif($language == 'en_EN' && $group == 'foo')
				{
					return array('teapot' => 'I\'m a teapot.', 'bar' => 'bar');
				}
				return array();
			}));

		$translator = new \Opc\Translate($cacheMock, $loaderMock);
		$translator->addLanguage('pl_PL', 10);
		$translator->addLanguage('en_EN', 0);
		$this->assertEquals('Jestem czajnikiem.', $translator->_('foo', 'teapot'));
		$this->assertEquals('bar', $translator->_('foo', 'bar'));
	} // end testMessageLoadingFromLoader();

	/**
	 * @cover \Opc\Translate::_
	 * @cover \Opc\Translate::assign
	 */
	public function testMessageFormatting()
	{
		$cacheMock = $this->getMock('\Opc\Translate\CachingInterface');
		$cacheMock->expects($this->once())
			->method('hasGroup')
			->with($this->stringContains('pl_PL'), $this->stringContains('foo'))
			->will($this->returnValue(false));

		$loaderMock = $this->getMock('\Opc\Translate\LoaderInterface');
		$loaderMock->expects($this->once())
			->method('loadLanguageGroup')
			->with($this->stringContains('pl_PL'), $this->stringContains('foo'))
			->will($this->returnValue(array('teapot' => 'HTCPCP {0,number} Jestem czajnikiem.')));

		$translator = new \Opc\Translate($cacheMock, $loaderMock);
		$translator->addLanguage('pl_PL', 10);
		$translator->assign('foo', 'teapot', array(418));
		$this->assertEquals('HTCPCP 418 Jestem czajnikiem.', $translator->_('foo', 'teapot'));
		$translator->assign('foo', 'teapot', array(417));
		$this->assertEquals('HTCPCP 417 Jestem czajnikiem.', $translator->_('foo', 'teapot'));
	} // end testMessageFormatting();
} // end Package_TranslateTest;