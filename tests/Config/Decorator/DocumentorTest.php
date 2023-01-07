<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\Config\Decorator;


class DocumentorTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$conf = new \Aimeos\Base\Config\PHPArray( [] );
		$this->object = new \Aimeos\Base\Config\Decorator\Documentor( $conf );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testGet()
	{
		$this->assertEquals( null, $this->object->get( 'notexisting' ) );
	}
}
