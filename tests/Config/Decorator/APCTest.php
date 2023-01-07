<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\Config\Decorator;


class APCTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$conf = new \Aimeos\Base\Config\PHPArray( [] );
		$this->object = new \Aimeos\Base\Config\Decorator\APC( $conf, 'test:' );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testGetSet()
	{
		$this->object->set( 'resource/db/host', '127.0.0.1' );
		$this->assertEquals( '127.0.0.1', $this->object->get( 'resource/db/host', '127.0.0.2' ) );
	}


	public function testGetDefault()
	{
		$this->assertEquals( 3306, $this->object->get( 'resource/db/port', 3306 ) );
	}
}
