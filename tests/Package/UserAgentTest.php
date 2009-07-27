<?php
/**
 * The tests for Opc_Visit_UserAgent.
 *
 * @author Jacek "eXtreme" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */

class Package_UserAgentTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	} // end setUp();

	protected function tearDown()
	{
	} // end tearDown();
	
	/**
	 * @dataProvider userAgentProvider
	 */
	public function testUserAgents($userAgent, $browser, $os)
	{
		$result = Opc_Visit_UserAgent::getInstance()->analyze($userAgent);
		
		foreach($result as $group => $fields)
		{
			foreach($fields as $key => $value)
			{
				$this->assertEquals(${$group}[$key], $value);
			}
		}
	}
	
	public static function userAgentProvider()
	{
		return array(
			// ========================== OPERA ==========================
			array('Opera/9.80 (Windows NT 5.1; U; pl) Presto/2.2.15 Version/10.00', 
				array('name'=>'Opera','version'=>'10.00'), array('os'=>'Windows','name'=>'XP')
			),
			array('Opera/10.00 (Windows NT 6.0; U; it) Presto/2.2.1', 
				array('name'=>'Opera','version'=>'10.00'), array('os'=>'Windows','name'=>'Vista')
			),
			array('Mozilla/5.0 (Windows NT 5.1; U; en; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 10.00', 
				array('name'=>'Opera','version'=>'10.00'), array('os'=>'Windows','name'=>'XP')
			),
			array('Opera/9.64 (Windows NT 6.1; U; de) Presto/2.1.1', 
				array('name'=>'Opera','version'=>'9.64'), array('os'=>'Windows','name'=>'7')
			),
			array('Mozilla/5.0 (Windows NT 6.1; U; en-GB; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 9.64', 
				array('name'=>'Opera','version'=>'9.64'), array('os'=>'Windows','name'=>'7')
			),
			array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; de) Opera 9.64', 
				array('name'=>'Opera','version'=>'9.64'), array('os'=>'Windows','name'=>'XP')
			),
			array('Opera/9.80 (X11; Linux x86_64; U; pl) Presto/2.2.15 Version/10.00', 
				array('name'=>'Opera','version'=>'10.00'), array('os'=>'Linux','name'=>'x86_64')
			),
			array('Opera/9.64 (X11; Linux i686; U; en) Presto/2.1.1', 
				array('name'=>'Opera','version'=>'9.64'), array('os'=>'Linux','name'=>'i686')
			),
			array('Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux x86_64; pl) Opera 10.00',
				array('name'=>'Opera','version'=>'10.00'), array('os'=>'Linux','name'=>'x86_64')
			),
			array('Opera/9.80 (Macintosh; Intel Mac OS X; U; en) Presto/2.2.15 Version/10.00', 
				array('name'=>'Opera','version'=>'10.00'), array('os'=>'Macintosh','name'=>'Mac OS X')
			),
			array('Mozilla/5.0 (Windows 98; U; en; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 9.64', 
				array('name'=>'Opera','version'=>'9.64'), array('os'=>'Windows','name'=>'98')
			),
			array('Opera/9.64 (Macintosh; Intel Mac OS X; U; de) Presto/2.1.1', 
				array('name'=>'Opera','version'=>'9.64'), array('os'=>'Macintosh','name'=>'Mac OS X')
			),
			/*array('', 
				array('name'=>'Opera','version'=>''), array('os'=>'','name'=>'')
			),*/
			// ========================= FIREFOX =============================
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.1.1) Gecko/20090718 Shiretoko/3.5.1', 
				array('name'=>'Firefox','version'=>'3.5.1'), array('os'=>'Linux','name'=>'x86_64')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1) Gecko/20090703 Shiretoko/3.5', 
				array('name'=>'Firefox','version'=>'3.5'), array('os'=>'Linux','name'=>'i686')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) Gecko/20121223 Ubuntu/9.25 (jaunty) Firefox/3.8', 
				array('name'=>'Firefox','version'=>'3.8'), array('os'=>'Linux','name'=>'Ubuntu','version'=>'9.25')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2a1pre) Gecko/20090428 Firefox/3.6a1pre', 
				array('name'=>'Firefox','version'=>'3.6a1pre'), array('os'=>'Linux','name'=>'x86_64')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1b4) Gecko/20090423 Firefox/3.5b4', 
				array('name'=>'Firefox','version'=>'3.5b4'), array('os'=>'Windows','name'=>'XP')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; de; rv:1.9.1.1) Gecko/20090722 Gentoo Firefox/3.5.1', 
				array('name'=>'Firefox','version'=>'3.5.1'), array('os'=>'Linux','name'=>'Gentoo')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; de; rv:1.9.1.1) Gecko/20090714 SUSE/3.5.1-1.1 Firefox/3.5.1', 
				array('name'=>'Firefox','version'=>'3.5.1'), array('os'=>'Linux','name'=>'SUSE','version'=>'3.5.1-1.1')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1 GTB5 (.NET CLR 4.0.20506)', 
				array('name'=>'Firefox','version'=>'3.5.1'), array('os'=>'Windows','name'=>'Vista')
			),
			array('Mozilla/5.0 (X11; U; DragonFly i386; de; rv:1.9.1b2) Gecko/20081201 Firefox/3.1b2', 
				array('name'=>'Firefox','version'=>'3.1b2'), array('os'=>'BSD','name'=>'DragonFly')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.1b3) Gecko/20090327 GNU/Linux/x86_64 Firefox/3.1', 
				array('name'=>'Firefox','version'=>'3.1'), array('os'=>'Linux','name'=>'GNU')
			),
			array('Mozilla/5.0 (X11; U; SunOS sun4u; en-US; rv:1.9b5) Gecko/2008032620 Firefox/3.0b5', 
				array('name'=>'Firefox','version'=>'3.0b5'), array('os'=>'BSD','name'=>'SunOS')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.0.8) Gecko/2009040312 Gentoo Firefox/3.0.8', 
				array('name'=>'Firefox','version'=>'3.0.8'), array('os'=>'Linux','name'=>'Gentoo')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.0.7) Gecko/2009032606 Red Hat/3.0.7-1.el5 Firefox/3.0.7', 
				array('name'=>'Firefox','version'=>'3.0.7'), array('os'=>'Linux','name'=>'Red Hat','version'=>'3.0.7-1.el5')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; es-ES; rv:1.9.0.6) Gecko/2009011912 Firefox/3.0.6', 
				array('name'=>'Firefox','version'=>'3.0.6'), array('os'=>'Macintosh','name'=>'Mac OS X', 'version'=>'10.5')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.1) Gecko/20061010 Firefox/3.0.6', 
				array('name'=>'Firefox','version'=>'3.0.6'), array('os'=>'Macintosh','name'=>'Mac OS X')
			),
			/*array('', 
				array('name'=>'Firefox','version'=>''), array('os'=>'','name'=>'')
			),*/
			// ======================= INTERNET EXPLORER ======================
			array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; Media Center PC 6.0; InfoPath.2; MS-RTC LM 8)', 
				array('name'=>'Internet Explorer','version'=>'8.0'), array('os'=>'Windows','name'=>'7')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; InfoPath.2)', 
				array('name'=>'Internet Explorer','version'=>'8.0'), array('os'=>'Windows','name'=>'7')
			),
			array('Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; el-GR)', 
				array('name'=>'Internet Explorer','version'=>'7.0'), array('os'=>'Windows','name'=>'Vista')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2)', 
				array('name'=>'Internet Explorer','version'=>'7.0'), array('os'=>'Windows','name'=>'Server 2003')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; Win64; x64; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 
				array('name'=>'Internet Explorer','version'=>'7.0'), array('os'=>'Windows','name'=>'XP')
			),
			array('Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4325)', 
				array('name'=>'Internet Explorer','version'=>'6.0'), array('os'=>'Windows','name'=>'XP')
			),
			array('Mozilla/4.0 (Windows; MSIE 6.0; Windows NT 5.0)', 
				array('name'=>'Internet Explorer','version'=>'6.0'), array('os'=>'Windows','name'=>'2000')
			),
			array('Mozilla/4.01 (compatible; MSIE 6.0; Windows NT 5.1)', 
				array('name'=>'Internet Explorer','version'=>'6.0'), array('os'=>'Windows','name'=>'XP')
			),
			array('Mozilla/4.0 (compatible; MSIE 5.50; Windows NT; SiteKiosk 4.8)', 
				array('name'=>'Internet Explorer','version'=>'5.50'), array('os'=>'Windows','name'=>'NT')
			),
			/*array('', 
				array('name'=>'Internet Explorer','version'=>''), array('os'=>'','name'=>'')
			),*/
			// ========================== SAFARI =====================
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7; en-us) AppleWebKit/530.19.2 (KHTML, like Gecko) Version/4.0.2 Safari/530.19', 
				array('name'=>'Safari','version'=>'4.0.2'), array('os'=>'Macintosh','name'=>'Mac OS X','version'=>'10_5_7')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/530.19.2 (KHTML, like Gecko) Version/4.0.2 Safari/530.19.1', 
				array('name'=>'Safari','version'=>'4.0.2'), array('os'=>'Windows','name'=>'Vista')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en) AppleWebKit/522+ (KHTML, like Gecko) Version/3.0.2 Safari/522.12', 
				array('name'=>'Safari','version'=>'3.0.2'), array('os'=>'Macintosh','name'=>'Mac OS X')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; nl) AppleWebKit/522.13.1 (KHTML, like Gecko) Version/3.0.2 Safari/522.13.1', 
				array('name'=>'Safari','version'=>'3.0.2'), array('os'=>'Windows','name'=>'Vista')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/418.9 (KHTML, like Gecko) Safari/419.3', 
				array('name'=>'Safari','version'=>'2'), array('os'=>'Macintosh','name'=>'Mac OS X')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/412 (KHTML, like Gecko) Safari/412', 
				array('name'=>'Safari','version'=>'2'), array('os'=>'Macintosh','name'=>'Mac OS X')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; de-ch) AppleWebKit/312.1 (KHTML, like Gecko) Safari/312', 
				array('name'=>'Safari','version'=>''), array('os'=>'Macintosh','name'=>'Mac OS X')
			),
			/*array('', 
				array('name'=>'Safari','version'=>''), array('os'=>'','name'=>'')
			),*/
			// ======================== OTHER =========================
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en; rv:1.9.0.8pre) Gecko/2009022800 Camino/2.0b3pre', 
				array('name'=>'Camino','version'=>'2.0b3pre'), array('os'=>'Macintosh','name'=>'Mac OS X','version'=>'10.5')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8.0.1) Gecko/20060119 Camino/1.0b2+', 
				array('name'=>'Camino','version'=>'1.0b2+'), array('os'=>'Macintosh','name'=>'Mac OS X')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.4) Gecko/20070509 Camino/1.5', 
				array('name'=>'Camino','version'=>'1.5'), array('os'=>'Macintosh','name'=>'Mac OS X')
			),
			array('Mozilla/5.0 (compatible; Konqueror/4.2; Linux) KHTML/4.2.1 (like Gecko) Fedora/4.2.1-4.fc11', 
				array('name'=>'Konqueror','version'=>'4.2'), array('os'=>'Linux','name'=>'Fedora','version'=>'4.2.1-4.fc11')
			),
			array('Mozilla/5.0 (compatible; Konqueror/4.1; OpenBSD) KHTML/4.1.4 (like Gecko)', 
				array('name'=>'Konqueror','version'=>'4.1'), array('os'=>'BSD','name'=>'OpenBSD')
			),
			array('Mozilla/5.0 (compatible; Konqueror/3.5; Windows NT 6.0) KHTML/3.5.6 (like Gecko)', 
				array('name'=>'Konqueror','version'=>'3.5'), array('os'=>'Windows','name'=>'Vista')
			),
			array('Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.6 (like Gecko) (Kubuntu)', 
				array('name'=>'Konqueror','version'=>'3.5'), array('os'=>'Linux','name'=>'Kubuntu')
			),
			array('Mozilla/5.0 (compatible; Konqueror/3.1; i686 Linux; 20021006)', 
				array('name'=>'Konqueror','version'=>'3.1'), array('os'=>'Linux','name'=>'')
			),
			/*array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; Avant Browser; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)', 
				array('name'=>'','version'=>''), array('os'=>'','name'=>'')
			),*/
			/*array('', 
				array('name'=>'','version'=>''), array('os'=>'','name'=>'')
			),*/
		);
	}
}