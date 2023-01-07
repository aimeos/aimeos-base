<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\Translation\Decorator;


class APCTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$trans = new \Aimeos\Base\Translation\None( 'en_GB' );
		$this->object = new \Aimeos\Base\Translation\Decorator\APC( $trans, 'i18n' );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testAll()
	{
		$this->assertEquals( [], $this->object->all( 'domain' ) );
	}


	public function testDt()
	{
		$this->assertEquals( 'test', $this->object->dt( 'domain', 'test' ) );
	}


	public function testDn()
	{
		$this->assertEquals( 'tests', $this->object->dn( 'domain', 'test', 'tests', 2 ) );
	}


	public function testGetLocale()
	{
		$this->assertEquals( 'en_GB', $this->object->getLocale() );
	}
}
