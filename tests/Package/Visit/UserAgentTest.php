<?php
/**
 * The tests for Opc_Visit_UserAgent.
 *
 * @author Jacek "eXtreme" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */

class Package_Visit_UserAgentTest extends PHPUnit_Framework_TestCase
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
				array('name'=>'Opera','version'=>'10.00','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Opera/10.00 (Windows NT 6.0; U; it) Presto/2.2.1', 
				array('name'=>'Opera','version'=>'10.00','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows NT 5.1; U; en; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 10.00', 
				array('name'=>'Opera','version'=>'10.00','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Opera/9.64 (Windows NT 6.1; U; de) Presto/2.1.1', 
				array('name'=>'Opera','version'=>'9.64','extra'=>''), array('system'=>'Windows','name'=>'7','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows NT 6.1; U; en-GB; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 9.64', 
				array('name'=>'Opera','version'=>'9.64','extra'=>''), array('system'=>'Windows','name'=>'7','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; de) Opera 9.64', 
				array('name'=>'Opera','version'=>'9.64','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Opera/9.80 (X11; Linux x86_64; U; pl) Presto/2.2.15 Version/10.00', 
				array('name'=>'Opera','version'=>'10.00','extra'=>''), array('system'=>'Linux','name'=>'x86_64','version'=>'','extra'=>'')
			),
			array('Opera/9.64 (X11; Linux i686; U; en) Presto/2.1.1', 
				array('name'=>'Opera','version'=>'9.64','extra'=>''), array('system'=>'Linux','name'=>'i686','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux x86_64; pl) Opera 10.00',
				array('name'=>'Opera','version'=>'10.00','extra'=>''), array('system'=>'Linux','name'=>'x86_64','version'=>'','extra'=>'')
			),
			array('Opera/9.80 (Macintosh; Intel Mac OS X; U; en) Presto/2.2.15 Version/10.00', 
				array('name'=>'Opera','version'=>'10.00','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'Intel')
			),
			array('Mozilla/5.0 (Windows 98; U; en; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 9.64', 
				array('name'=>'Opera','version'=>'9.64','extra'=>''), array('system'=>'Windows','name'=>'98','version'=>'','extra'=>'')
			),
			array('Opera/9.64 (Macintosh; Intel Mac OS X; U; de) Presto/2.1.1', 
				array('name'=>'Opera','version'=>'9.64','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'Intel')
			),
			/*array('', 
				array('name'=>'Opera','version'=>'','extra'=>''), array('system'=>'','name'=>'')
			),*/
			// ========================= FIREFOX =============================
			array('Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) Gecko/20121223 Ubuntu/9.25 (jaunty) Firefox/3.8', 
				array('name'=>'Firefox','version'=>'3.8','extra'=>''), array('system'=>'Linux','name'=>'Ubuntu','version'=>'9.25','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2a1pre) Gecko/20090428 Firefox/3.6a1pre', 
				array('name'=>'Firefox','version'=>'3.6a1pre','extra'=>''), array('system'=>'Linux','name'=>'x86_64','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1b4) Gecko/20090423 Firefox/3.5b4', 
				array('name'=>'Firefox','version'=>'3.5b4','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; de; rv:1.9.1.1) Gecko/20090722 Gentoo Firefox/3.5.1', 
				array('name'=>'Firefox','version'=>'3.5.1','extra'=>''), array('system'=>'Linux','name'=>'Gentoo','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; de; rv:1.9.1.1) Gecko/20090714 SUSE/3.5.1-1.1 Firefox/3.5.1', 
				array('name'=>'Firefox','version'=>'3.5.1','extra'=>''), array('system'=>'Linux','name'=>'SUSE','version'=>'3.5.1-1.1','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1 GTB5 (.NET CLR 4.0.20506)', 
				array('name'=>'Firefox','version'=>'3.5.1','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; DragonFly i386; de; rv:1.9.1b2) Gecko/20081201 Firefox/3.1b2', 
				array('name'=>'Firefox','version'=>'3.1b2','extra'=>''), array('system'=>'BSD','name'=>'DragonFly','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.1b3) Gecko/20090327 GNU/Linux/x86_64 Firefox/3.1', 
				array('name'=>'Firefox','version'=>'3.1','extra'=>''), array('system'=>'Linux','name'=>'GNU','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; SunOS sun4u; en-US; rv:1.9b5) Gecko/2008032620 Firefox/3.0b5', 
				array('name'=>'Firefox','version'=>'3.0b5','extra'=>''), array('system'=>'BSD','name'=>'SunOS','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.0.8) Gecko/2009040312 Gentoo Firefox/3.0.8', 
				array('name'=>'Firefox','version'=>'3.0.8','extra'=>''), array('system'=>'Linux','name'=>'Gentoo','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.0.7) Gecko/2009032606 Red Hat/3.0.7-1.el5 Firefox/3.0.7', 
				array('name'=>'Firefox','version'=>'3.0.7','extra'=>''), array('system'=>'Linux','name'=>'Red Hat','version'=>'3.0.7-1.el5','extra'=>'')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; es-ES; rv:1.9.0.6) Gecko/2009011912 Firefox/3.0.6', 
				array('name'=>'Firefox','version'=>'3.0.6','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'10.5','extra'=>'Intel')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.1) Gecko/20061010 Firefox/3.0.6', 
				array('name'=>'Firefox','version'=>'3.0.6','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'Intel')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.1.1) Gecko/20090718 Shiretoko/3.5.1', 
				array('name'=>'Firefox','version'=>'3.5.1','extra'=>'Shiretoko'), array('system'=>'Linux','name'=>'x86_64','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1) Gecko/20090703 Shiretoko/3.5', 
				array('name'=>'Firefox','version'=>'3.5','extra'=>'Shiretoko'), array('system'=>'Linux','name'=>'i686','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:2.0a1pre) Gecko/2008060602 Minefield/4.0a1pre', 
				array('name'=>'Firefox','version'=>'4.0a1pre','extra'=>'Minefield'), array('system'=>'Linux','name'=>'i686','version'=>'','extra'=>'')
			),
			/*array('', 
				array('name'=>'Firefox','version'=>''), array('system'=>'','name'=>'')
			),*/
			// ======================= INTERNET EXPLORER ======================
			array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; Media Center PC 6.0; InfoPath.2; MS-RTC LM 8)', 
				array('name'=>'Internet Explorer','version'=>'8.0','extra'=>''), array('system'=>'Windows','name'=>'7','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; InfoPath.2)', 
				array('name'=>'Internet Explorer','version'=>'8.0','extra'=>''), array('system'=>'Windows','name'=>'7','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; el-GR)', 
				array('name'=>'Internet Explorer','version'=>'7.0','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2)', 
				array('name'=>'Internet Explorer','version'=>'7.0','extra'=>''), array('system'=>'Windows','name'=>'Server 2003','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; Win64; x64; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 
				array('name'=>'Internet Explorer','version'=>'7.0','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4325)', 
				array('name'=>'Internet Explorer','version'=>'6.0','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (Windows; MSIE 6.0; Windows NT 5.0)', 
				array('name'=>'Internet Explorer','version'=>'6.0','extra'=>''), array('system'=>'Windows','name'=>'2000','version'=>'','extra'=>'')
			),
			array('Mozilla/4.01 (compatible; MSIE 6.0; Windows NT 5.1)', 
				array('name'=>'Internet Explorer','version'=>'6.0','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 5.50; Windows NT; SiteKiosk 4.8)', 
				array('name'=>'Internet Explorer','version'=>'5.50','extra'=>''), array('system'=>'Windows','name'=>'NT','version'=>'','extra'=>'')
			),
			/*array('', 
				array('name'=>'Internet Explorer','version'=>''), array('system'=>'','name'=>'')
			),*/
			// ========================== SAFARI =====================
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7; en-us) AppleWebKit/530.19.2 (KHTML, like Gecko) Version/4.0.2 Safari/530.19', 
				array('name'=>'Safari','version'=>'4.0.2','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'10_5_7','extra'=>'Intel')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/530.19.2 (KHTML, like Gecko) Version/4.0.2 Safari/530.19.1', 
				array('name'=>'Safari','version'=>'4.0.2','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en) AppleWebKit/522+ (KHTML, like Gecko) Version/3.0.2 Safari/522.12', 
				array('name'=>'Safari','version'=>'3.0.2','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'Intel')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; nl) AppleWebKit/522.13.1 (KHTML, like Gecko) Version/3.0.2 Safari/522.13.1', 
				array('name'=>'Safari','version'=>'3.0.2','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/418.9 (KHTML, like Gecko) Safari/419.3', 
				array('name'=>'Safari','version'=>'2','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'PPC')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/412 (KHTML, like Gecko) Safari/412', 
				array('name'=>'Safari','version'=>'2','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'PPC')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; de-ch) AppleWebKit/312.1 (KHTML, like Gecko) Safari/312', 
				array('name'=>'Safari','version'=>'','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'PPC')
			),
			/*array('', 
				array('name'=>'Safari','version'=>''), array('system'=>'','name'=>'')
			),*/
			// ======================= CHROME =========================
			array('Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.1 Safari/532.0', 
				array('name'=>'Chrome','version'=>'3.0.195.1','extra'=>''), array('system'=>'Linux','name'=>'i686','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.1 Safari/532.0', 
				array('name'=>'Chrome','version'=>'3.0.195.1','extra'=>''), array('system'=>'Windows','name'=>'7','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/0.4.154.31 Safari/525.19', 
				array('name'=>'Chrome','version'=>'0.4.154.31','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/530.1 (KHTML, like Gecko) Iron/2.0.168.0 Safari/530.1', 
				array('name'=>'Iron','version'=>'2.0.168.0','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			/*array('', 
				array('name'=>'Chrome','version'=>''), array('system'=>'','name'=>'')
			),*/
			// ======================== OTHER =========================
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en; rv:1.9.0.8pre) Gecko/2009022800 Camino/2.0b3pre', 
				array('name'=>'Camino','version'=>'2.0b3pre','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'10.5','extra'=>'Intel')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8.0.1) Gecko/20060119 Camino/1.0b2+', 
				array('name'=>'Camino','version'=>'1.0b2+','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'Mach-O','extra'=>'PPC')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.4) Gecko/20070509 Camino/1.5', 
				array('name'=>'Camino','version'=>'1.5','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'Intel')
			),
			array('Mozilla/5.0 (compatible; Konqueror/4.2; Linux) KHTML/4.2.1 (like Gecko) Fedora/4.2.1-4.fc11', 
				array('name'=>'Konqueror','version'=>'4.2','extra'=>''), array('system'=>'Linux','name'=>'Fedora','version'=>'4.2.1-4.fc11','extra'=>'')
			),
			array('Mozilla/5.0 (compatible; Konqueror/4.1; OpenBSD) KHTML/4.1.4 (like Gecko)', 
				array('name'=>'Konqueror','version'=>'4.1','extra'=>''), array('system'=>'BSD','name'=>'OpenBSD','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (compatible; Konqueror/3.5; Windows NT 6.0) KHTML/3.5.6 (like Gecko)', 
				array('name'=>'Konqueror','version'=>'3.5','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.6 (like Gecko) (Kubuntu)', 
				array('name'=>'Konqueror','version'=>'3.5','extra'=>''), array('system'=>'Linux','name'=>'Kubuntu','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (compatible; Konqueror/3.1; i686 Linux; 20021006)', 
				array('name'=>'Konqueror','version'=>'3.1','extra'=>''), array('system'=>'Linux','name'=>'Generic','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.8.1.8pre) Gecko/20070928 Firefox/2.0.0.7 Navigator/9.0RC1', 
				array('name'=>'Netscape','version'=>'9.0RC1','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.12) Gecko/20080219 Firefox/2.0.0.12 Navigator/9.0.0.6', 
				array('name'=>'Netscape','version'=>'9.0.0.6','extra'=>''), array('system'=>'Linux','name'=>'i686','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 5.0; de-DE; rv:1.0.2) Gecko/20030208 Netscape/7.02', 
				array('name'=>'Netscape','version'=>'7.02','extra'=>''), array('system'=>'Windows','name'=>'2000','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 5.0; de-DE; rv:0.9.4.1) Gecko/20020508 Netscape6/6.2.3', 
				array('name'=>'Netscape','version'=>'6.2.3','extra'=>''), array('system'=>'Windows','name'=>'2000','version'=>'','extra'=>'')
			),
			array('Lynx (textmode)', 
				array('name'=>'Lynx','version'=>'','extra'=>''), array('system'=>'unknown','name'=>'','version'=>'','extra'=>'')
			),
			array('Lynx/2.8.6rel.5 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/0.9.8g', 
				array('name'=>'Lynx','version'=>'2.8.6rel.5','extra'=>''), array('system'=>'unknown','name'=>'','version'=>'','extra'=>'')
			),
			array('Links (6.9; Unix 6.9-astral sparc; 80x25)', 
				array('name'=>'Links','version'=>'6.9','extra'=>''), array('system'=>'Unix','name'=>'6.9-astral sparc','version'=>'','extra'=>'')
			),
			array('Links (2.2; Linux 2.6.27-hardened-r7 x86_64; x)', 
				array('name'=>'Links','version'=>'2.2','extra'=>''), array('system'=>'Linux','name'=>'2.6.27-hardened-r7 x86_64','version'=>'','extra'=>'')
			),
			array('ELinks/0.9.3 (textmode; Linux 2.6.9-kanotix-8 i686; 127x41)', 
				array('name'=>'ELinks','version'=>'0.9.3','extra'=>''), array('system'=>'Linux','name'=>'2.6.9-kanotix-8 i686','version'=>'','extra'=>'')
			),
			array('ELinks (0.4pre6; Linux 2.2.19ext3 alpha; 80x25)', 
				array('name'=>'ELinks','version'=>'0.4pre6','extra'=>''), array('system'=>'Linux','name'=>'2.2.19ext3 alpha','version'=>'','extra'=>'')
			),
			array('ELinks/0.13.GIT (textmode; Linux 2.6.24-1-686 i686; 138x60-2)', 
				array('name'=>'ELinks','version'=>'0.13.GIT','extra'=>''), array('system'=>'Linux','name'=>'2.6.24-1-686 i686','version'=>'','extra'=>'')
			),
			array('ELinks/0.11.3-8ubuntu3 (textmode; Debian; Linux 2.6.27-11-generic i686; 80x25-2)', 
				array('name'=>'ELinks','version'=>'0.11.3-8ubuntu3','extra'=>''), array('system'=>'Linux','name'=>'Debian','version'=>'','extra'=>'')
			),
			array('ELinks (textmode)', 
				array('name'=>'ELinks','version'=>'','extra'=>''), array('system'=>'unknown','name'=>'','version'=>'','extra'=>'')
			),
			array('Links (2.2; Linux 2.6.24.4-desktop586-3mnb i686; x)', 
				array('name'=>'Links','version'=>'2.2','extra'=>''), array('system'=>'Linux','name'=>'2.6.24.4-desktop586-3mnb i686','version'=>'','extra'=>'')
			),
			array('Links (2.1pre28; Linux 2.6.22-14-generic i686; x)', 
				array('name'=>'Links','version'=>'2.1pre28','extra'=>''), array('system'=>'Linux','name'=>'2.6.22-14-generic i686','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.8) Gecko/20090327 Galeon/2.0.7', 
				array('name'=>'Galeon','version'=>'2.0.7','extra'=>''), array('system'=>'Linux','name'=>'i686','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux sparc64; en-GB; rv:1.8.1.11) Gecko/20071217 Galeon/2.0.3 Firefox/2.0.0.11', 
				array('name'=>'Galeon','version'=>'2.0.3','extra'=>''), array('system'=>'Linux','name'=>'sparc64','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; ja-jp) AppleWebKit/419 (KHTML, like Gecko) Shiira/1.2.3 Safari/125', 
				array('name'=>'Shiira','version'=>'1.2.3','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'PPC')
			),
			array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-us) AppleWebKit/523.15.1 (KHTML, like Gecko) Shiira Safari/125', 
				array('name'=>'Shiira','version'=>'','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'PPC')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en; rv:1.9.0.8) Gecko/20080528 Fedora/2.24.3-4.fc10 Epiphany/2.22 Firefox/3.0', 
				array('name'=>'Epiphany','version'=>'2.22','extra'=>''), array('system'=>'Linux','name'=>'Fedora','version'=>'2.24.3-4.fc10','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; en; rv:1.9.0.7) Gecko/20080528 Epiphany/2.22', 
				array('name'=>'Epiphany','version'=>'2.22','extra'=>''), array('system'=>'Linux','name'=>'x86_64','version'=>'','extra'=>'')
			),
			array('iCab/4.5 (Macintosh; U; PPC Mac OS X)', 
				array('name'=>'iCab','version'=>'4.5','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'PPC')
			),
			array('Mozilla/5.0 (compatible; iCab 3.0.5; Macintosh; U; PPC Mac OS X)', 
				array('name'=>'iCab','version'=>'3.0.5','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'PPC')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.21) Gecko/20090331 K-Meleon/1.5.3', 
				array('name'=>'K-Meleon','version'=>'1.5.3','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; Acoo Browser; GTB6; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; InfoPath.1; .NET CLR 3.5.30729; .NET CLR 3.0.30618)', 
				array('name'=>'Acoo Browser','version'=>'','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Acoo Browser; GTB5; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; Maxthon; InfoPath.1; .NET CLR 3.5.30729; .NET CLR 3.0.30618)', 
				array('name'=>'Acoo Browser','version'=>'','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; Avant Browser; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)', 
				array('name'=>'Avant Browser','version'=>'','extra'=>''), array('system'=>'Windows','name'=>'7','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser; .NET CLR 2.0.50727; MAXTHON 2.0)', 
				array('name'=>'MAXTHON','version'=>'2.0','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Avant Browser; .NET CLR 1.0.3705; .NET CLR 1.1.4322; .NET CLR 2.0.50727)', 
				array('name'=>'Avant Browser','version'=>'','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 8.0; AOL 9.5; AOLBuild 4337.43; Windows NT 6.0; Trident/4.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.21022; .NET CLR 3.5.30729; .NET CLR 3.0.30618)', 
				array('name'=>'AOL','version'=>'9.5','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.5; AOLBuild 4337.43; Windows NT 5.1; .NET CLR 1.1.4322)', 
				array('name'=>'AOL','version'=>'9.5','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux; de-DE) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.8.0', 
				array('name'=>'Arora','version'=>'0.8.0','extra'=>''), array('system'=>'Linux','name'=>'Generic','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux; es-ES) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.4 (Change: 388 835b3b6)', 
				array('name'=>'Arora','version'=>'0.4','extra'=>''), array('system'=>'Linux','name'=>'Generic','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; InfoPath.2; .NET CLR 2.0.50727; .NET CLR 1.1.4322; Crazy Browser 3.0.0 Beta2)', 
				array('name'=>'Crazy Browser','version'=>'3.0.0','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser; .NET CLR 2.0.50727; .NET CLR 3.0.04506.590; .NET CLR 3.5.20706; Crazy Browser 2.0.1)', 
				array('name'=>'Crazy Browser','version'=>'2.0.1','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008092921 IceCat/3.0.3-g1', 
				array('name'=>'IceCat','version'=>'3.0.3-g1','extra'=>''), array('system'=>'Linux','name'=>'i686','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.1) Gecko/20090701 Firefox/3.5 Lunascape/5.1.2.3', 
				array('name'=>'Lunascape','version'=>'5.1.2.3','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; ja-JP) AppleWebKit/528+ (KHTML, like Gecko, Safari/528.0) Lunascape/5.1.2.0', 
				array('name'=>'Lunascape','version'=>'5.1.2.0','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30618; MAXTHON 2.0)', 
				array('name'=>'MAXTHON','version'=>'2.0','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; Maxthon; .NET CLR 2.0.50727; .NET CLR 1.1.4322; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', 
				array('name'=>'Maxthon','version'=>'','extra'=>''), array('system'=>'Windows','name'=>'XP','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; fr-fr) AppleWebKit/525.1+ (KHTML, like Gecko, Safari/525.1+) midori/1.19', 
				array('name'=>'midori','version'=>'1.19','extra'=>''), array('system'=>'Linux','name'=>'i686','version'=>'','extra'=>'')
			),
			array('Midori/0.1.8 (X11; Linux x86_64; U; en-us) WebKit/532+', 
				array('name'=>'Midori','version'=>'0.1.8','extra'=>''), array('system'=>'Linux','name'=>'x86_64','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; fr-fr) AppleWebKit/525.1+ (KHTML, like Gecko, Safari/525.1+) midori', 
				array('name'=>'midori','version'=>'','extra'=>''), array('system'=>'Linux','name'=>'x86_64','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9a3pre) Gecko/20070330', 
				array('name'=>'Mozilla','version'=>'1.9a3pre','extra'=>''), array('system'=>'Linux','name'=>'i686','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.0.7) Gecko/2009021910 MEGAUPLOAD 1.0', 
				array('name'=>'Mozilla','version'=>'1.9.0.7','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US) AppleWebKit/528.16 (KHTML, like Gecko, Safari/528.16) OmniWeb/v622.8.0.112941', 
				array('name'=>'OmniWeb','version'=>'v622.8.0.112941','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'','extra'=>'Intel')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.1) Gecko/20090722 Firefox/3.5.1 Orca/1.2 build 2', 
				array('name'=>'Orca','version'=>'1.2','extra'=>''), array('system'=>'Windows','name'=>'7','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.9.1b4pre) Gecko/20090419 SeaMonkey/2.0b1pre', 
				array('name'=>'SeaMonkey','version'=>'2.0b1pre','extra'=>''), array('system'=>'Windows','name'=>'2000','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_6; en-us) AppleWebKit/528.16 (KHTML, like Gecko) Stainless/0.5.3 Safari/525.20.1', 
				array('name'=>'Stainless','version'=>'0.5.3','extra'=>''), array('system'=>'Mac OS','name'=>'X','version'=>'10_5_6','extra'=>'Intel')
			),
			array('Mozilla/5.0 (X11; U; Linux armv7l; en-US; rv:1.9.2a1pre) Gecko/20090322 Fennec/1.0b2pre', 
				array('name'=>'Fennec','version'=>'1.0b2pre','extra'=>''), array('system'=>'Linux','name'=>'armv7l','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (X11; U; Linux x86_64; es-AR; rv:1.9.0.2) Gecko/2008091920 Firefox/3.0.2 Flock/2.0b3', 
				array('name'=>'Flock','version'=>'2.0b3','extra'=>''), array('system'=>'Linux','name'=>'x86_64','version'=>'','extra'=>'')
			),
			array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.2) Gecko/2008092122 Firefox/3.0.2 Flock/2.0b3', 
				array('name'=>'Flock','version'=>'2.0b3','extra'=>''), array('system'=>'Windows','name'=>'Vista','version'=>'','extra'=>'')
			),
			array('some unknown browser here', 
				array('name'=>'unknown','version'=>'','extra'=>''), array('system'=>'unknown','name'=>'','version'=>'','extra'=>'')
			),
			/*array('', 
				array('name'=>'','version'=>''), array('system'=>'','name'=>'')
			),*/
		);
	}
}