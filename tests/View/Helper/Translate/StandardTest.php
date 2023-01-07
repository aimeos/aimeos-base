<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\View\Helper\Translate;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();
		$translate = new \Aimeos\Base\Translation\None( 'en_GB' );
		$this->object = new \Aimeos\Base\View\Helper\Translate\Standard( $view, $translate );
	}


	protected function tearDown() : void
	{
		$this->object = null;
	}


	public function testTransform()
	{
		$this->assertEquals( 'File', $this->object->transform( 'test', 'File', 'Files', 1 ) );
		$this->assertEquals( 'Files', $this->object->transform( 'test', 'File', 'Files', 2 ) );

		$this->assertNull( $this->object->transform( 'test', 'File', null, 0, false ) );
		$this->assertNull( $this->object->transform( 'test', 'File', 'Files', 2, false ) );
	}
}
